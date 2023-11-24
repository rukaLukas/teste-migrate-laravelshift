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
                    O pedido de adesão de seu município à plataforma Busca Ativa Vacinal foi enviado com sucesso. <br/>
                    Por favor, confirme a adesão no botão abaixo.
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
