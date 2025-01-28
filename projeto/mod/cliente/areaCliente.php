<h3 style="text-align: center">Bem-vindo <?= $_SESSION['NomeCliente'] ?>!</h3>
<div style="display: flex; justify-content: center; align-items: center; text-align: center; min-height: 85vh;">
    <a type="button" class="btn btn-warning me-3" href="?m=cliente&a=infoCliente">Editar Informações Pessoais</a>
    <a type="button" class="btn btn-warning me-3" href="?m=cliente&a=clienteReservas">As suas reservas</a>
    <a type="button" class="btn btn-warning" href="?m=cliente&a=estadopagamento">Os meus Pagamentos</a>
</div>
