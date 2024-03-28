## English README for Dutch Vocabulary Application

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

`CREATE DATABASE nachtmerrie;
USE nachtmerrie;
SHOW DATABASES;

CREATE TABLE item;
CREATE TABLE item (id int primary key auto_increment, nl varchar(255), de varchar(255));
CREATE TABLE sentence (id int primary key auto_increment, item_id int, nl varchar(255), de varchar(255));
ALTER TABLE sentence ADD FOREIGN KEY(item_id) REFERENCES item(id); 
DESC item;`

10. Create the directory 'nachtmerrie' in /var/www/
11. Clone the Git repository.

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


