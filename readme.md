Following will be details of the project and tools that were used involving the completion of project.

Currently deployed in UQ zone.

Tools used
CodeIgniter 3.1.11
Bootstrap 4.6.0
Jquery 3.6.0

Configuration required depending on environment (CodeIgniter)
config.php
$config['base_url'] - new website link

autoload.php
$autoload['helper'] - array('url','form','cookie')
$autoload['libraries'] - array('session', 'database', 'email', 'table')

Database related
Dataabse name is project

Links related
Do wide search on files for https://infs3202-8a85b4a1.uqcloud.net/project/ - since new deployed environment will have different link

Codes used to create tables in MySQL
To create users table
CREATE TABLE Users (
  UserID int NOT NULL AUTO_INCREMENT,
  Username varchar(255) NOT NULL UNIQUE,
  Password varchar(255) NOT NULL,
  Email varchar(255) NOT NULL UNIQUE,
  Phone_Number varchar(255),
  PRIMARY KEY (UserID, Username) 
);
