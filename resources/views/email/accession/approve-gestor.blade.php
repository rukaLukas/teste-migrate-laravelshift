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
                    Sua solicitação de adesão à plataforma da BAV foi aprovada. Clique no botão
                    abaixo para configurar seu uso.
                    <br/><br/>
                    <br/><br/>
                    <a class="btna" href="{{$url}}" target="_blank">
                        Iniciar acesso à BAV
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
