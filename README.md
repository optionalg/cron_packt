# cron_packt
Allows you to get book from packtpub.com


##How do I use it?

This i very simple. Download file to your server/computer. Change values for two defines located in line number 3 and 4 to your packtub login and password. Like so:

```php
define('LOGIN', 'mylogin@example.com');
define('PASSWORD', 'my53cre7pa55');
```

Then user `crontab` or other scheduler to execute this script once a day. Ex using crontab from linux console.

```bash
crontab -e
```
And place this line at the end of file in a new line.
```bash
0 7 * * * php /path/to/cron/file/cron.php
````

This will execute cron.php every day at 7AM and after successful operation will send an email to you with title and description of today's free book.

