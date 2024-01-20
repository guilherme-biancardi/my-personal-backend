<?php

// lang/pt_BR/messages.php

return [
    'auth' => [
        'not_credentials' => 'E-mail e/ou senha inválidos',
        'not_authorized' => 'Usuário não autorizado',
        'not_activated' => 'Usuário não ativado, fale com seu administrador e tente novamente',
        'logged_in' => 'Usuário logado com sucesso!',
        'logged_out' => 'Usuário deslogado com sucesso!',
        'created' => 'Usuário criado com sucesso!',
        'not_owner_permission' => 'Usuário não tem permissão para criar outros usuários.',
        'reset_password' => [
            'success' => 'Senha alterada com sucesso!',
            'failure' => 'Não foi possível alterar sua senha'
        ]
    ],

    'user' => [
        'link_expired' => 'Este link de ativação expirou',
        'activated_sucess' => 'Usuário ativado com sucesso!',
        'already_active' => 'Este usuário já está ativo',
        'link_sent' => 'Verifique sua caixa de e-mail'
    ],

    'seller' => [
        'created' => ':name foi cadastrado como vendedor com sucesso!',
        'deleted' => 'Vendedor removido com sucesso',
        'restored' => 'Vendedor restaurado com sucesso!', 
        'edited' => 'Vendedor alterado com sucesso!',
        'not_found_on_delete' => 'Vendedor removido ou não existente',
        'not_found_on_restore' => 'Vendedor ativo ou não existente'
    ],

    'device_model' => [
        'created' => ':model foi cadastrado com sucesso!',
        'deleted' => 'Modelo removido com sucesso',
        'contains_devices' => 'o modelo possui dispositivos cadastrados',
        'devices_deleted' => 'todos os dispositivos deste modelo foram removidos',
        'device_deleted' => 'Dispositivo foi removido deste modelo',
        'not_found_on_delete' => 'Modelo removido ou não existente',
    ],

    'device' => [
        'created' => 'Dispositivo cadastrado com sucesso!',
        'updated' => 'Dispositivo alterado com sucesso!',
        'deleted' => 'Dispositivo removido com sucesso!',
        'not_found' => 'Dispositivo não encontrado',
        'exists' => 'Dispositivo já cadastrado'
    ]
];
