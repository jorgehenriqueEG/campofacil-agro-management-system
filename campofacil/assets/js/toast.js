function criarToast(mensagem, tipo = 'erro'){

    const toast = document.createElement('div');

    toast.classList.add('toast');

    if(tipo === 'sucesso'){
        toast.classList.add('toast-sucesso');
    }else{
        toast.classList.add('toast-erro');
    }

    toast.innerText = mensagem;

    document.body.appendChild(toast);

    setTimeout(() => {

        toast.classList.add('mostrar');

    }, 100);

    setTimeout(() => {

        toast.classList.remove('mostrar');

        setTimeout(() => {

            toast.remove();

        }, 300);

    }, 5000);
}

function verificarProdutosCriticos(produtos){

    produtos.forEach(produto => {

        if(produto.estoque_baixo){

            criarToast(

                `${produto.nome} acabará em aproximadamente ${produto.dias_restantes} dias.`,

                'erro'
            );
        }
    });
}