export default {
    data() {
        return {
            HTTPCodeMessage: {
                "100": "Continue",
                "101": "Protocolo de comutação",
                "103": "Dicas iniciais",
                "200": "OK",
                "201": "Um novo recurso foi criado",
                "202": "Conteudo Aceito",
                "203": "Informações não autorizadas",
                "204": "Sem conteúdo",
                "205": "Redefinir conteúdo",
                "206": "Conteúdo parcial",
                "300": "Múltipla escolha",
                "301": "Movido Permanentemente",
                "302": "Encontrado",
                "303": "Ver outros",
                "304": "Não modificado",
                "307": "Redirecionamento temporário",
                "308": "Redirecionamento permanente",
                "400": "Pedido ruim",
                "401": "Não autorizado",
                "402 ": "Pagamento Requerido",
                "403": "Recurso Proibido",
                "404": "Recurso Não encontrado",
                "405": "Método não permitido",
                "406": "Recurso Não aceitável",
                "407": "Autenticação de proxy necessária",
                "408": "Solicitar tempo limite",
                "409": "Conflito",
                "410": "conteúdo requisitado foi permanentemente deletado",
                "411": "Comprimento Necessário",
                "412": "Falha na pré-condição",
                "413": "Carga muito grande",
                "414": "URI muito longo",
                "415": "Tipo de mídia não suportado",
                "416": "Intervalo Solicitado Não Satisfável",
                "418": "Expectativa falhou",
                "418": "eu sou um bule?",
                "422": "Conteudo não processável",
                "423": "O recurso sendo acessado está travado.",
                "424": "A requisição falhou devido a falha em requisição prévia.",
                "425": "Muito cedo",
                "426": "Atualização necessária",
                "428": "Pré-condição necessária",
                "429": "Muitos pedidos",
                "431": "Campos de cabeçalho de solicitação muito grandes",
                "451": "Indisponível por motivos legais",
                "500": "Erro do Servidor Interno",
                "501": "Não implementado",
                "502": "Gateway inválido",
                "503": "Serviço não disponível",
                "504": "Tempo limite do gateway",
                "505": "Versão HTTP não suportada",
                "506": "Variante também negocia",
                "507": "Armazenamento insuficiente",
                "508": "O servidor detectou um looping infinito ao processar a requisição.",
                "510": "Não estendido",
                "511": "Autenticação de rede necessária",
            }
        }
    },
    methods: {
        GetUrl(api = true) {
            if (!api) {
                return location.origin+location.pathname
            }
            return location.origin+"/api"+location.pathname
        },
        FormataValor(val) {
            if (val) {
                if (!isNaN(val.toString().substr(0, 1)) && val.toString().indexOf("-") !== -1) {
                    val = val.split('-').reverse().join('/');
                }
                if (!isNaN(val.toString().substr(0, 1)) && val.toString().indexOf(".") !== -1) {
                    val.trim()
                    val = parseFloat(val).toLocaleString('pt-BR', {
                        style: "currency",
                        currency: 'BRL'
                    }).substr(3)
                }
            }
            
            return val
        },
        FormataValorInputBanco(valor) {
            if (valor) {
                if (!isNaN(valor.substr(0, 1)) && valor.indexOf(",") !== -1) {
                    if (valor.toString().indexOf(',') !== -1) {
                        valor = valor.replace(/\./g, '').replace(/\,/g, '.');
                    }
                    valor = Number(valor);
                }
            }
            return valor;
        },
        TraduzHTTPCode(statusText) {
            return this.HTTPCodeMessage[statusText];
        }
    },
}