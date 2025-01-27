function formatarCPF(input) {
    // Remove todos os caracteres que não são dígitos
    let valor = input.value.replace(/\D/g, '');
    // Formata o CPF no formato xxx.xxx.xxx-xx
    if (valor.length > 11) {
        valor = valor.substring(0, 11);
    }
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{2})$/, '$1-$2');
    input.value = valor;
}

function formatarTelefone(input) {
// Remove todos os caracteres que não são dígitos
let valor = input.value.replace(/\D/g, '');
// Formata o número no formato (xx) xxxxx-xxxx
if (valor.length > 10) {
    valor = valor.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
} else if (valor.length > 5) {
    valor = valor.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
} else if (valor.length > 2) {
    valor = valor.replace(/(\d{2})(\d{0,5})/, '($1) $2');
} else if (valor.length > 0) {
    valor = valor.replace(/(\d+)/, '($1');
}
input.value = valor;
}

function formatarCEP(input) {
    // Remove todos os caracteres que não são dígitos
    let valor = input.value.replace(/\D/g, '');
    // Limita o valor a 8 dígitos
    if (valor.length > 8) {
        valor = valor.substring(0, 8);
    }
    // Formata o CEP no formato xxxxx-xxx
    valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
    input.value = valor;
}