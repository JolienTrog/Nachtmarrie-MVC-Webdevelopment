## nachtmerrie: Dutch Vocabulary Application

### Overview

This application helps you learn Dutch vocabulary by providing words, example sentences, and the ability to track your progress.

### Getting Started

1. Create a SQL database and name it `nachtmerrie`.
2. Use the database: `USE nachtmerrie;`
3. Check that the database was created: `SHOW DATABASES;`
4. Create a table for the items: `CREATE TABLE item;`
5. Create the `item` table with the following columns:
    - `id` (int, primary key, auto-increment)
    - `nl` (varchar(255))
    - `de` (varchar(255))
6. Create a table for the sentences: `CREATE TABLE sentence;`
7. Create the `sentence` table with the following columns:
    - `id` (int, primary key, auto-increment)
    - `item_id` (int)
    - `nl` (varchar(255))
    - `de` (varchar(255))
8. Add a foreign key to the `sentence` table: `ALTER TABLE sentence ADD FOREIGN KEY(item_id) REFERENCES item(id);`
9. Get the schema of the `item` table: `DESC item;`

```
CREATE DATABASE nachtmerrie;
USE nachtmerrie;
SHOW DATABASES;

CREATE TABLE item;
CREATE TABLE item (id int primary key auto_increment, nl varchar(255), de varchar(255));
CREATE TABLE sentence (id int primary key auto_increment, item_id int, nl varchar(255), de varchar(255));
ALTER TABLE sentence ADD FOREIGN KEY(item_id) REFERENCES item(id); 
DESC item;
```


10. To create and edit a new project in the /var/www directory, the owner of the project directory must be changed.

`sudo chown username:www-data /var/www/nachtmerrie -R`

Create the directory 'nachtmerrie' in /var/www/

`mkdir /var/www/nachtmerrie`

11. Clone the Git repository.

`git clone https://git.netways.de/jtrog/nachtmerrie.git`

12. Install apache2 as webserver
```
sudo apt install apache2
sudo systemctl start apache2
```

13. Change Virtual Host configuration in `/etc/apache2/sites-available/000-default.conf`

<details><summary>file</summary>
VirtualHost *:80>
ServerAdmin webmaster@localhost
DocumentRoot /var/www/nachtmerrie/public

ErrorLog ${APACHE_LOG_DIR}/error.log
CustomLog ${APACHE_LOG_DIR}/access.log combined
<Directory /var/www/nachtmerrie/public>
Options Indexes FollowSymLinks
AllowOverride All
Require all granted
</Directory>
</VirtualHost>
</details>


Generate a new vocabulary list.

### Using the Application

**Buttons:**

- `terug` - Back
- `woorden leren` - Learn Vocabulary
- `nieuw woord` - New Word
- `nieuw woordenlijst` - New Vocabulary List

**Features:**

- Learn Dutch vocabulary with words and example sentences.
- Add new words and sentences to the database.
- Generate new vocabulary lists based on your learning progress.

### Version 2 Ideas

- Automated translation
- Integration of user-defined JSON files
- Learning status display
- User login

### Future Plans

This is the first simple version of the application. Future plans include:

- Implementing the features listed in "Version 2 Ideas"
- Adding more features to help users learn Dutch vocabulary more effectively
- Making the application more user-friendly and accessible

### Contributing

If you have any suggestions or bug reports, please feel free to open an issue on the GitHub repository.

### License


