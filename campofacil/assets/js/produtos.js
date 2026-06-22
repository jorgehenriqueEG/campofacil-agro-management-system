async function carregarProdutos(busca = ''){

    try{

        const resposta =
        await fetch(
            `../api/produtos.php?busca=${busca}`
        );

        const dados =
        await resposta.json();

        renderizarProdutos(
            dados.produtos
        );

    }catch(erro){

        console.error(
            'Erro produtos:',
            erro
        );
    }
}

function renderizarProdutos(produtos){

    const grid =
    document.getElementById(
        'grid-produtos'
    );

    grid.innerHTML = '';

    if(produtos.length === 0){

        grid.innerHTML = `

            <div class="prod">

                Nenhum produto encontrado.

            </div>

        `;

        return;
    }

    produtos.forEach(produto => {

        const badge = produto.estoque_baixo

        ? `
            <div class="badge badge-baixo">
                🔴 Estoque Baixo
            </div>
        `

        : `
            <div class="badge badge-ok">
                🟢 Estoque Saudável
            </div>
        `;

        const classe =
        produto.estoque_baixo
        ? 'prod prod-baixo'
        : 'prod';

        grid.innerHTML += `

        <div class="${classe}">

            <h3>
                ${produto.nome}
            </h3>

            <p>
                💰 R$
                ${produto.valor.toFixed(2)}
            </p>

            <p>
                📦 Estoque:
                <strong>
                    ${produto.estoque}
                </strong>
            </p>

            <p>
                ⚠ Mínimo:
                ${produto.estoque_minimo}
            </p>

            <p>
                ⏱ Consumo:
                ${produto.consumo_medio}/dia
            </p>

            <p>
                🔁 Base:
                ${produto.dias_consumo} dias
            </p>

            ${badge}

            <br><br>

            <a
            href="?excluir=${produto.id}"
            onclick="return confirm('Desativar produto?')"
            >

                Desativar

            </a>

        </div>

        `;
    });
}

const busca =
document.getElementById(
    'buscaProduto'
);

busca.addEventListener('keyup', () => {

    carregarProdutos(
        busca.value
    );
});

carregarProdutos();