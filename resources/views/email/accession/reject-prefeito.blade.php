@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <p class="footer-text">
                    <b>Prezado(a), {{$pronoun}} {{$name}} </b>
                </p>
                <br/>
                <h4 class="footer-text">
                    A equipe da Busca Ativa Vacinal analisaram sua solicitação de adesão à plataforma da BAV <br/>
                    e optaram pela sua rejeição com a seguinte justificativa:
                    <br/><br/>
                    {{$rejection_description}}
                    <br/>
                    <br/><br/>
                    Se o município desejar aderir à plataforma, é possível iniciar o processo novamente, observando </br>
                    as informações prestadas pelos nossos técnicos.
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
