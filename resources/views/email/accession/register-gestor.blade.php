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
                    O pedido de adesão de seu município à plataforma Busca Ativa Vacial foi enviado com sucesso.
                    <br/><br/>
                    <br/><br/>
                    Nossa equipe analisará os dados e se manifestará em até <b>5 dias úteis após a confirmação
                    da adesão pelo(a) prefeito(a).</b> Se a adesão for aprovada, você receberá, no e-mail
                    cadastrado, autorização para acessar a plataforma e cadastrar seus primeiros usuários.
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
