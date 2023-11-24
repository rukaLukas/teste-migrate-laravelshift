@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <p class="footer-text">
                    <b>Olá, {{$pronoun}} {{$name}} </b>
                </p>
                <br/>
                <h4 class="footer-text">
                    Este é um convite para acessar o Sistema BAV: Busca
                    Ativa Vacinal, do UNICEF. Por favor, confirme seu cadastro clicando no botão abaixo.
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
