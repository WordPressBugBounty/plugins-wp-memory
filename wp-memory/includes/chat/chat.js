jQuery(document).ready(function ($) {
    // console.log("DEBUG: Chat script started."); // LOG 1: Script iniciou

    let chatVersion = "2.00";
    const billChatMessages = $('#chat-messages');
    const billChatForm = $('#chat-form');
    const billChatInput = $('#chat-input');
    const billChaterrorMessage = $('#error-message');
    let billChatLastMessageCount = 0;

    // =========================================================================
    // NOVO CÓDIGO - Início da Lógica de Animação de Botão (VERSÃO COM CICLO)
    // =========================================================================
    // console.log("DEBUG: Initializing animation logic with recurring cycle.");

    const autoCheckupButtons = $('#auto-checkup, #auto-checkup2');
    if (autoCheckupButtons.length === 0) {
        // console.error("DEBUG-ERROR: No buttons found with selectors '#auto-checkup' or '#auto-checkup2'.");
    }

    let inactivityTimer;
    let userHasInteracted = false;

    // Função que dispara a animação e agenda o próximo ciclo
    function triggerPulseAnimation() {
        if (userHasInteracted) return; // Para se o usuário já interagiu

        // console.log("DEBUG: Inactivity detected! Applying .pulse-button class.");
        autoCheckupButtons.addClass('pulse-button');

        // O CSS fará a animação tocar 3 vezes (dura 6 segundos no total).
        // Usamos um setTimeout para agendar a próxima verificação APÓS a animação terminar.
        // Vamos esperar 7 segundos (6s da animação + 1s de margem)
        // E então, se o usuário ainda estiver inativo, agendamos o próximo pulso.
        setTimeout(function () {
            // Primeiro, removemos a classe para que possa ser adicionada novamente no futuro
            autoCheckupButtons.removeClass('pulse-button');

            // Se o usuário ainda não interagiu, agenda o próximo ciclo após um intervalo maior
            if (!userHasInteracted) {
                // console.log("DEBUG: Animation cycle finished. Scheduling next pulse check in 15 seconds.");
                inactivityTimer = setTimeout(triggerPulseAnimation, 15000); // Próximo pulso em 15s
            }
        }, 6000); // 6 segundos = 2s de duração da animação * 3 repetições
    }

    // Função para redefinir o ciclo de inatividade (quando o usuário está ativo)
    function resetInactivityTimer() {
        if (userHasInteracted) return;

        // console.log("DEBUG: User activity detected. Resetting inactivity timer. First pulse in 8 seconds.");

        // Limpa qualquer temporizador pendente (seja o inicial de 8s ou o de 15s)
        clearTimeout(inactivityTimer);

        // Remove a classe de pulso caso a atividade ocorra DURANTE a animação
        autoCheckupButtons.removeClass('pulse-button');

        // Agenda o PRIMEIRO pulso para daqui a 8 segundos
        inactivityTimer = setTimeout(triggerPulseAnimation, 8000);
    }

    // Função para parar permanentemente a funcionalidade de animação
    function stopAnimationFeature() {
        // console.log("DEBUG: Disabling animation feature permanently.");
        userHasInteracted = true;
        clearTimeout(inactivityTimer);
        autoCheckupButtons.removeClass('pulse-button');
    }

    // Event listeners para detectar atividade do usuário
    $(document).on('mousemove keypress click', resetInactivityTimer);

    // Inicia o processo quando a página carrega
    resetInactivityTimer();

    // =========================================================================
    // NOVO CÓDIGO - Fim da Lógica de Animação
    // =========================================================================

    function billChatEscapeHtml(text) {
        return $('<div>').text(text).html();
    }

    // Clears the chat on the server
    $.ajax({
        url: bill_data.ajax_url,
        method: 'POST',
        data: { action: 'bill_chat_reset_messages' },
        success: function () { },
        error: function (xhr, status, error) { console.error(bill_data.reset_error, error, xhr.responseText); }
    });

    function billChatLoadMessages() {
        $.ajax({
            url: bill_data.ajax_url,
            method: 'POST',
            data: { action: 'bill_chat_load_messages', last_count: billChatLastMessageCount },
            success: function (response, status, xhr) {
                try {
                    if (typeof response === 'string') { response = JSON.parse(response); }
                    if (Array.isArray(response.messages)) {
                        if (response.message_count > billChatLastMessageCount) {
                            billChatLastMessageCount = response.message_count;
                            response.messages.forEach(function (message) {
                                if (message.text && message.sender) {
                                    if (message.sender === 'user') {
                                        billChatMessages.append('<div class="user-message">' + billChatEscapeHtml(message.text) + '</div>');
                                    } else if (message.sender === 'chatgpt') {
                                        let processedText = billChatEscapeHtml(message.text);
                                        processedText = processedText.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                                        billChatMessages.append('<div class="chatgpt-message">' + processedText + '</div>');
                                    }
                                }
                            });
                            billChatMessages.scrollTop(billChatMessages[0].scrollHeight);
                            $('.spinner999').css('display', 'none');
                            setTimeout(function () { $('#chat-form button').prop('disabled', false); }, 2000);
                        }
                    } else {
                        console.error(bill_data.invalid_response_format, response);
                        $('.spinner999').css('display', 'none');
                        $('#chat-form button').prop('disabled', false);
                    }
                } catch (err) {
                    console.error(bill_data.response_processing_error, err, response);
                    $('.spinner999').css('display', 'none');
                    $('#chat-form button').prop('disabled', false);
                }
            },
            error: function (xhr, status, error) {
                console.error(bill_data.ajax_error, error, xhr.responseText);
                $('.spinner999').css('display', 'none');
                $('#chat-form button').prop('disabled', false);
            },
        });
    }


    $('#chat-form button').on('click', function (e) {
        e.preventDefault();

        // console.log("DEBUG: Chat form button clicked."); // LOG 8: Botão do formulário clicado
        stopAnimationFeature(); // Para a funcionalidade de animação na primeira interação

        const clickedButtonId = $(this).attr('id');
        const message = billChatInput.val().trim();
        const chatType = (clickedButtonId === 'auto-checkup' || clickedButtonId === 'auto-checkup2')
            ? clickedButtonId
            : ($('#chat-type').length ? $('#chat-type').val() : 'default');

        if ((chatType === 'auto-checkup' || chatType === 'auto-checkup2') || (chatType !== 'auto-checkup' && chatType !== 'auto-checkup2' && message !== '')) {
            $('.spinner999').css('display', 'block');
            $('#chat-form button').prop('disabled', true);
            $.ajax({
                url: bill_data.ajax_url,
                method: 'POST',
                data: { action: 'bill_chat_send_message', message: message, chat_type: chatType, chat_version: chatVersion },
                timeout: 60000,
                success: function () {
                    setTimeout(function () { billChatInput.val(''); }, 2000);
                    billChatLoadMessages();
                },
                error: function (xhr, status, error) {
                    billChaterrorMessage.text(bill_data.send_error).show();
                    $('.spinner999').css('display', 'none');
                    $('#chat-form button').prop('disabled', false);
                    setTimeout(() => billChaterrorMessage.fadeOut(), 5000);
                }
            });
        } else {
            billChaterrorMessage.text(bill_data.empty_message_error).show();
            setTimeout(() => billChaterrorMessage.fadeOut(), 3000);
        }
    });

    setInterval(() => {
        if (billChatMessages.is(':visible')) {
            billChatLoadMessages();
        }
    }, 3000);

    billChatMessages.empty();
});