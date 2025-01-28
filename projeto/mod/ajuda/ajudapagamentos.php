<head>
    <script>
        function openMap(location) {
            const mapUrls = {
                "Aveiro": "https://www.google.com/maps/place/Ciclo+Criativo+-+Edificio+Antigo+Col%C3%A9gio/@40.7575718,-8.5739031,17z/data=!3m1!4b1!4m6!3m5!1s0xd239b80e959b98d:0x7049ad098d4989aa!8m2!3d40.7575718!4d-8.5739031!16s%2Fg%2F11c431yqxy?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D",
                "Porto": "https://www.google.com/maps/place/Aeroporto+Francisco+S%C3%A1+Carneiro/@41.2466296,-8.6836,15.07z/data=!4m6!3m5!1s0xd246f64614a2bad:0x151a578a6d6039d1!8m2!3d41.2473992!4d-8.6806638!16zL20vMDlwXzYw?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D",
                "Lisboa": "https://www.google.com/maps/place/Aeroporto+Internacional+de+Lisboa+Humberto+Delgado/@38.7639084,-9.1562925,12.95z/data=!4m6!3m5!1s0xd19324616d90183:0xa66a53e58036d46!8m2!3d38.7788454!4d-9.1319758!16zL20vMDM3MzA5?entry=ttu&g_ep=EgoyMDI0MTIxMS4wIKXMDSoASAFQAw%3D%3D"
            };

            const mapUrl = mapUrls[location];
            window.open(mapUrl, '_blank');
        }
    </script>
</head>

<div class="text-center">
    <h4>Precisa de ajuda com os seus pagamentos?</h4>
    <br>
    <h5>Aqui tentamos responder a todas as suas questões!</h5>
</div>
<br>
<div class="text-center">
    <p>Se ainda não efetuou o pagamento da sua reserva, este deve ser feito de forma presencial, com antecedência, no local onde irá proceder à recolha da viatura.</p>
    <p>Locais de recolha e pagamento:</p>
  
    <a href="javascript:void(0);" onclick="openMap('Aveiro')">Aveiro</a>
    <br>
    <a href="javascript:void(0);" onclick="openMap('Porto')">Porto</a>
    <br>
    <a href="javascript:void(0);" onclick="openMap('Lisboa')">Lisboa</a>
    <br>
</div>
