<html lang="pt-br">

<body>
    <h1>Novo acesso solicitado!</h1>
    <h3>O usuário <span style="color: #27ae60">{{ $user->name }}</span> está solicitando acesso ao sistema</h3>
    <p style="font-size: 14px">Clique no abaixo para concluir o cadastro.</p>
    <a href="{{ $url }}"
        style="display: block; height: 36px; width: 240px; background-color: #27ae60; color: #fff; border-radius: 8px; text-decoration: none;text-align: center; line-height: 36px; font-size: 14px;">Clique
        Aqui</a>
    <span>
        <p>Caso o botão não estiver sendo exibido corretamente acesse copie ou acesse esta URL:</p>
        <a href="{{ $url }}">{{ $url }}</a>
    </span>
</body>

</html>
