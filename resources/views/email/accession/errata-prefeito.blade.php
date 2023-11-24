@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <h3 class="footer-text">
                    ERRATA | BAV | Solicitação de adesão PENDENTE - Prefeito
                </h3>
                <p class="footer-text">
                    <b>Olá, {{$pronoun}} {{$name}}</b>
                </p>
                <br/>
                <h4 class="footer-text">
                    Em e-mail enviado anteriormente, foi informado que a sua solicitação de adesão à plataforma da BAV
                    foi aprovada para seu município. Contudo, o status do seu município ainda está como PENDENTE.
                    Estamos aguardando o aceite dos TERMOS DA ADESÃO e a validação da equipe da BAV dos dados do(a)
                    prefeito(a). Por favor, confirme a adesão no botão abaixo.
                    <br/><br/>
                    <br/><br/>
                    <a class="btna" href="{{$url}}" target="_blank">
                        Confirmar adesão
                    </a>
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
