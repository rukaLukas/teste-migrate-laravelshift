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
                    Foi encerrado na BAV o caso/registro de uma criança do município de {{$city}}.                                       
                    <br/><br/>                    
                    <b>Encerrado por:</b> {{$who}} <br>
                    <b>Data/horário:</b> {{$when}}<br>
                    <b>Motivo:</b> {{$reason}}<br>
                    <b>Observação:</b> {{$comment}}<br>

                    <br/>Para mais informações, entre em contato com o gestor municipal da BAV.<br/>
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
