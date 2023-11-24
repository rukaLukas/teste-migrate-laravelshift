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
                    Foi registrado na BAV uma criança do município de {{$city}} com atraso vacinal pelo motivo de {{$reason}}. Para mais informações, entre em contato com o gestor municipal da BAV.                   
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
