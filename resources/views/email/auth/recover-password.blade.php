@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <p class="footer-text">
                    <b>Olá, {{$name}} </b>
                </p>
                <br/>
                <h4 class="footer-text">
                    Você fez uma solicitação de troca de senha. <br>Para completar o procedimento, clique no botão abaixo
                    <br/><br/>
                    <br/><br/>
                    <a class="btna" href="{{$url}}" target="_blank">
                        Trocar Senha
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
