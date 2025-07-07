### Teste Revenda Mais

API Restful desenvolvida em Laravel 12 para gerenciamento de fornecedores.

#### Tecnologias utilizadas.

- Docker
- caddy(web server)
- PHP 8.4
- PostgreSQL
- Redis

### Como rodar o projeto:

Para rodar o projeto pode-se usar 2 metodos:
1. rodar o comando `docker compose up -d --build`
2.  ou o `./run.sh` que além de rodar o docker compose irá executar o compose install e as migrations.

Caso tenha optado pelo 1 os demais comandos serão necessários para continuar a execução:

- Instala as dependencias: `docker exec -it app_php sh -c "composer install --working-dir=/var/www/revendamais"`
- Copia o .env a partir do exemplo: `docker exec -it app_php sh -c "cp revendamais/.env.example revendamais/.env"`
- Gera a app key: `docker exec -it app_php sh -c "php revendamais/artisan key:generate"`
- Executa os migrations: `docker exec -it app_php sh -c "php revendamais/artisan migrate:fresh --seed"`


Para ambos os casos para a execução dos testes os seguintes comandos terão que ser executados:

- Gerar a key do env de testes: `docker exec -it app_php sh -c "php revendamais/artisan key:generate --env=testing"`
- Executar os testes: `docker exec -it app_php sh -c "cd revendamais; php artisan test"`


## Endpoints

Há um arquivo chamado [httpie-collection-revendamais.json](httpie-collection-revendamais.json) na raiz do projeto que pode ser importado usando o HTTPie ou o arquivo [Revendamais.postman_collection.json](Revendamais.postman_collection.json) para o Postman que pode ser utilizado com as requests prontas para uso ou pode-se usar também os métodos abaixo.

Listagem de fornecedores:

Os parametros abaixo são opcionais e oferecem paginação e filtros para a listagem:
- `order` (asc ou desc)
- `order_by` (name ou email)
- `per_page` (numero inteiro)
- `page`    (numero inteiro)

GET /api/suppliers
```
curl -k --request GET --url 'https://revendamais.localhost/api/suppliers?order=asc&order_by=name&per_page=5&page=1'
```

Criação de Fornecedor:

O corpo da request segue o exemplo abaixo e é compartilhado com a atualização de um fornecedor:

```
  -d "name=Fornecedor Teste" \         # Nome do fornecedor
  -d "type=CNPJ" \                     # Tipo de documento (CPF ou CNPJ)
  -d "document=12345678000199" \      # Número do documento
  -d "email=teste@fornecedor.com" \   # Email
  -d "phone=11999999999" \            # Telefone
  -d "street=Rua Exemplo 123" \       # Dados de endereço
  -d "post_code=01001000" \           # CEP
  -d "city=São Paulo" \               # Cidade
  -d "country=BR" \                   # País
  -d "state=SP"                       # Estado
```
 
POST /api/suppliers
```
curl -k --request POST --url https://revendamais.localhost/api/suppliers -H "Content-Type: application/x-www-form-urlencoded"   -d "name=Fornecedor 1"   -d "type=CNPJ"   -d "document=41724783000133"   -d "email=fornecedor@teste.com"   -d "phone=99999999999"   -d "street=Rua TesteA Lote 5"   -d "post_code=69099480"   -d "city=Maringá"   -d "country=BR"   -d "state=Paraná"
```

Listagem Idividual de Fornecedor:
GET /api/suppliers/{id}
```
curl -k --request GET \
  --url https://revendamais.localhost/api/suppliers/1
```

Update:
PATCH /api/suppliers/{id}
```
curl -k --request PATCH --url https://revendamais.localhost/api/suppliers/1 -H "Content-Type: application/x-www-form-urlencoded"   -d "name=Fornecedor 2"   -d "type=CNPJ"   -d "document=41724783000133"   -d "email=fornecedor@teste.com"   -d "phone=99999999999"   -d "street=Rua TesteA Lote 5"   -d "post_code=69099480"   -d "city=Maringá"   -d "country=BR"   -d "state=Paraná"
```

Delete:
DELETE /api/suppliers/{id}
```
curl -k --request DELETE \
  --url https://revendamais.localhost/api/suppliers/1
```

Busca de CNPJ:
GET /api/externalsearch/{cnpj}
```
curl -k --request GET \
  --url https://revendamais.localhost/api/externalsearch/41724783000133
```