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
                    A solicitação de adesão à plataforma da BAV foi aprovada para seu
                    município. A pessoa responsável pela gestão política
                    municipal também receberá um e-mail com as instruções de acesso
                    e configuração da plataforma.
                    <br/><br/>
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
