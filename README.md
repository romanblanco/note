Note
====
Smal tool written in PHP for saving quick notes.  
![Note example](/files/img/example.png)

To every note is assigned tag, so when you have more notes, you can show just that one, that have same tag.  
You can also easily search between your notes with searchbar. Searching is done with SQL REGEXP and during finding looks to matching expression in `topic`, `note`, `tag` and `date` 

Shortcuts:
----------
for shortcuts is used java-script library [Mousetrap](http://craig.is/killing/mice
 "Mousetrap")

`/`      -- Search  
`n`      -- New note  
`^`      -- Go on top of page  
`G` `H`  -- Go on homepage  

Install:
--------
To start using just place downloaded folder on your server, edit login data to your database at top of [handle.php](/handle.php "handle.php") file  and then create tables in MySQL. All needed SQL command is in file [install.sql](/install.sql "install.sql")

Privacy:
--------
Login and registration of users is not done yet, but Note can use `$_SERVER[REMOTE_USER]` as name for user, so there is easy option to protect notes or add more users with [htaccess](http://httpd.apache.org/docs/current/howto/htaccess.html) and [htpasswd](http://httpd.apache.org/docs/2.2/programs/htpasswd.html).

#### htaccess example:
    AuthName "Password Required"
    AuthUserFile /var/www/note/.htpasswd
    AuthType Basic
    AuthGroupFile /dev/null
    require valid-user

#### htpasswd example:
    user1:$apr1$t8vYn16a$bLI1q6bX4lci98D17D/Tl/
    user2:$apr1$wL94fjHZ$C7ryR7aj4JoqHlhFVKztD1

username: user1  
password: 0000

username: user2  
password: 1111

New user you can generate with command `htpasswd -c passwdfile username`