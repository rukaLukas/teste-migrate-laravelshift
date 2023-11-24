@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <p class="footer-text">
                    <b>Olá,</b>
                </p>
                <br/>
                <h4 class="footer-text">
                    Olá, esta mensagem é uma notificação do encaminhamento de alerta do BAV. abaixo descrição do motivo do encaminhamento:<br>
                    {{$description}}
                    <br/><br/>
                    <b>Nome da criança:</b> {{$name}} <br>
                    <b>CPF:</b> {{$cpf}}<br>
                    <b>Cartão SUS:</b> {{$suscard}}<br>
                    <b>Observação:</b> {{$description}}<br>
                    <br/><br/>
                </h4>
            </tr>
            <tr>
                <td colspan="3">

                </td>
            </tr>
        </table>
    </td>
</tr>

@include('email.default.footer')
