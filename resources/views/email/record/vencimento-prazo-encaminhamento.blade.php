@include('email.default.header')

<tr>
    <td class="content">
        <table role="presentation" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <br/>
                <p class="footer-text">
                    <b>Olá</b> mãe/responsável pai/responsável do(a) <strong>{{$nomeCrianca}}</strong>,
                    a <strong>Busca Ativa Vacinal</strong>
                    do município <strong>{{$nomeMunicipio}}</strong> veio te lembrar que seu(ua) filho(a)
                    está com as seguintes vacinas em atrasos:
                </p>
                <p class="footer-text">
                    @foreach($vacinas as $vacina)
                        {{ $vacina['name']}} / {{$vacina['dose']}}º dose <br/>
                    @endforeach
                </p>
                <p class="footer-text">
                    Dirija-se até a sala de vacina da <strong>{{$nomeUbs}}</strong> no prazo de até <strong>{{$days}}</strong> dias: <br/>
                    Local: <strong>{{$enderecoUbs}}</strong> <br/>
                    Não se esqueça de levar a carteirinha de vacinação. <br/>
                    Esperamos você lá! <br/>
                    Caso você já tenha aplicado essas vacinas, desconsidere esta mensagem. <br/><br/>
                    <strong>Vacinas Protegem. Vacinas salvam vidas!</strong>
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
