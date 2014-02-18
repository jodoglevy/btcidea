btcidea
=======

### Description
BtcIdea description TBD

### Installation
#### Dependencies
BtcIdea depends on the following components:
- A web server that supports PHP 5.5 or later, with HTTPS configured
- A local or remote MySQL database server
- An Amazon Web Services subscription, for using the AWS Simple Email Service (SES)

#### Configuration
Set the following environment variables on the web server that will be hosting BtcIdea:
- **CUSTOMCONNSTR_EncryptionKey** - A random 32 character string of uppercase letters, lowercase letters, and numbers. This is used for data encryption.
- **MYSQLCONNSTR_BtcDbConnection** - The connection string for the MySQL server, in the format *Database=database-name;Data Source=MySQL-server-hostname-or-IP;User Id=username;Password=password*
- **CUSTOMCONNSTR_AwsSesSmtp** - Credentials with access to the AWS SES service, in the format *{"SMTP_Username": "username-goes-here", "SMTP_Password": "password-goes-here"}*

### License
This work is licensed under a Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License. You can read the full license [here](http://creativecommons.org/licenses/by-nc-sa/4.0/legalcode) or a human-readable version [here](http://creativecommons.org/licenses/by-nc-sa/4.0/deed.en_US).
