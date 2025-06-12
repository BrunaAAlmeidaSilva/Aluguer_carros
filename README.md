# Aluguer de Carros ANURB CARS S.A.- Sistema de Reservas de Automóveis

Este projeto é um sistema de aluguer de automóveis desenvolvido em Laravel, com autenticação, área do cliente, gestão de reservas, pagamentos simulados e lógica de reembolso/cobrança automática ao editar/cancelar reservas.

## Funcionalidades

- Pesquisa e filtragem de automóveis disponíveis
- Reserva de automóveis com datas e locais personalizados
- Autenticação de utilizadores (registo, login, logout)
- Área do cliente com histórico e gestão de reservas
- Edição de datas da reserva com recálculo automático do valor
- Cancelamento de reservas com lógica de reembolso (>48h)
- Pagamento simulado (Multibanco)
- Geração de PDF com detalhes da reserva
- Validação de datas (máx. 7 meses à frente, data de levatamento < devolução)
- Mensagens dinâmicas de valores a receber/pagar ao editar reservas
- API Recaptcha

## Instalação

1. **Clonar o repositório:**
   ```bash
   git clone <repo-url>
   cd Aluguer_carros
   ```
2. **Instalar dependências:**
   ```bash
   composer install
   npm install && npm run dev
   ```
3. **Configurar o ambiente:**
   - Copie `.env.example` para `.env` e configure a base de dados.
   
4. **Migrar e popular a base de dados:**
   ```bash
   php artisan migrate //+ idealmente correr inserts em MySQL, da pasta app/Script/locacao_carros, para criar dados
   ```

   Fazem parte dos inserts 2 users diferentes caso nao queira fazer novo registo: 
   'António Guterres','antonioguterres@anurb.com', "password"
   'Rosa Mota','rosamota@anurb.com', "password"
   
5. **Iniciar o servidor:**
   ```bash
   php artisan serve
   ```

## Estrutura Principal

- `app/Http/Controllers/` - Lógica dos controladores (Reserva, Auth, Cliente, Pagamento)
- `app/Models/` - Modelos Eloquent (Reserva, BemLocavel, User, etc)
- `resources/views/` - Vistas Blade (Reserva, Cliente, Home, CarroEscolha etc)
- `routes/web.php` - Rotas principais da aplicação
- `database/migrations/` - Estrutura da base de dados 

## Fluxo de Reserva

1. O utilizador escolhe o veículo, as datas e localizaçao.
2. Se não estiver autenticado, é redirecionado para login/registo (os dados da reserva são mantidos).
3. Após login, pode concluir o pagamento.
4. Na área do cliente pode editar/cancelar reservas:
   - Ao editar datas, o valor é recalculado e mostra se há diferença a pagar ou a receber.
   - Ao cancelar, só há reembolso se faltar mais de 48h para o início.

## Observações

- O sistema está preparado para ser facilmente adaptado a pagamentos reais.
- O layout é responsivo e moderno.
- O código segue boas práticas Laravel.

## Contacto
Dúvidas ou sugestões: [brunaanjosalmeida@gmail.com]
