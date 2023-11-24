@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <h3 class="footer-text">
                    ERRATA | BAV | Solicitação de adesão PENDENTE - Gestor
                </h3>
                <br/>
                <p class="footer-text">
                    <b>Prezado(a), {{$pronoun}} {{$name}} ESTA É UMA ERRATA </b>
                </p>
                <br/>
                <h4 class="footer-text">
                    Em e-mail enviado anteriormente, foi informado que a solicitação de adesão à plataforma da BAV foi
                    aprovada para seu município. Contudo, o status do seu município ainda está como PENDENTE devido ao
                    fato de estarmos aguardando o aceite dos TERMOS DA ADESÃO e validação da equipe da BAV dos dados
                    do(a) prefeito(a). Solicite para o(a) prefeito(a) aceitar o termo enviado por e-mail. Enquanto isso,
                    confirme sua adesão no link abaixo:
                    <br/><br/>
                    <br/><br/>
                    <a class="btna" href="{{$url}}" target="_blank">
                        Iniciar acesso à BAV
                    </a>
                </h4>
                <p class="footer-text">
                    Importante: As funcionalidades da plataforma BAV estarão temporariamente desabilitadas até que a
                    pendência apresentada seja resolvida.
                </p>
            </tr>
            <tr>
                <td colspan="3">

                </td>
            </tr>
        </table>
    </td>
</tr>

@include('email.default.footer')
