let graficoVendas = null;

async function carregarDashboard(){
    try{
        const resposta = await fetch('../api/dashboard.php');
        const dados = await resposta.json();

        atualizarCards(dados.cards);
        atualizarProdutosBaixos(dados.produtos_baixos);
        atualizarUltimasVendas(dados.ultimas_vendas);
        atualizarGrafico(dados.grafico_vendas);

        if (typeof verificarProdutosCriticos === "function" && dados.produtos_baixos) {
            verificarProdutosCriticos(dados.produtos_baixos);
        }

    }catch(erro){
        console.error('Erro dashboard:', erro);
    }
}

function atualizarCards(cards){
    document.getElementById('card-clientes').innerText = cards.clientes;
    document.getElementById('card-vendas').innerText = cards.vendas;
    document.getElementById('card-produtos').innerText = cards.produtos;
    document.getElementById('card-estoque').innerText = cards.estoque_baixo;
}

function atualizarProdutosBaixos(produtos){
    const container = document.getElementById('produtos-baixos');
    container.innerHTML = '';

    if(produtos.length === 0){
        container.innerHTML = `
            <div class="alerta-ok">
                Nenhum produto com estoque baixo.
            </div>
        `;
        return;
    }

    produtos.forEach(produto => {
        container.innerHTML += `
            <div class="alerta-item">
                Produto: ${produto.nome} | Estoque: ${produto.estoque} | Mínimo: ${produto.estoque_minimo}
            </div>
        `;
    });
}

function atualizarUltimasVendas(vendas){
    const tbody = document.getElementById('tbody-vendas');
    tbody.innerHTML = '';

    vendas.forEach(venda => {
        tbody.innerHTML += `
        <tr>
            <td>${venda.fazenda}</td>
            <td>
                <span class="tag-location">
                    ${venda.cidade} - ${venda.uf}
                </span>
            </td>
            <td>${venda.produto}</td>
            <td>
                <span class="tag-qtd">
                    ${venda.quantidade}
                </span>
            </td>
            <td>
                ${formatarData(venda.data_venda)}
            </td>
        </tr>
        `;
    });
}

function atualizarGrafico(dados){
    const ctx = document.getElementById('graficoVendas');

    if(!ctx){
        return;
    }

    const labels = dados.map(item => item.nome);
    const valores = dados.map(item => item.total);

    if(graficoVendas){
        graficoVendas.data.labels = labels;
        graficoVendas.data.datasets[0].data = valores;
        graficoVendas.update();
        return;
    }

    graficoVendas = new Chart(ctx, {
        type:'bar',
        data:{
            labels: labels,
            datasets:[{
                label:'Quantidade Vendida',
                data: valores,
                borderWidth:1
            }]
        },
        options:{
            responsive:true,
            plugins:{
                legend:{
                    labels:{
                        color:'#fff'
                    }
                }
            },
            scales:{
                x:{
                    ticks:{
                        color:'#fff'
                    }
                },
                y:{
                    ticks:{
                        color:'#fff'
                    }
                }
            }
        }
    });
}

function formatarData(data){
    return data;
}

carregarDashboard();

setInterval(carregarDashboard, 10000);