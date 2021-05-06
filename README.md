# adAccounts
## Launch 
### docker-compose build
### docker-compose up
## Test
### localhost:8085/api/doc
### token - "admin"
### User creation payload example
{
  "email": "123",
  "roles": [
    {"0":"ROLE_USER"}
  ],
  "password": "string",
  "name": "string",
  "active": true,
  "accounts": [
    {"1": "EDIT"}
  ]
}
