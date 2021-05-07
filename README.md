# adAccounts
## Launch 
### docker-compose build
### docker-compose up
## Test
### localhost:8085/api/doc
### token - "admin"
### User creation payload example
{
  "email": "user@gmail.com",
  "roles": [
    {"0":"ROLE_USER"}
  ],
  "password": "pass",
  "name": "user",
  "active": true,
  "accounts": [
    {"1": "EDIT"}
  ]
}
