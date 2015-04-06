# online-programming-judge
An online programming judge for an college or community. The web app runs on a LAMP stack.

## Dependencies
1. Latest gcc and g++ for compiling submitted programs on server side.
2. XAMPP.

## Installing the application.

### Installing the app.
1. Install XAMPP.
2. In the htdocs folder do a git clone of the repository or copy the source files in a folder.
3. Give appropriate permissions to the user on this folder.

### Installing the database.
1. Create a new user in mysql.
2. Create a new database for the app. Default database name is CODERS.
3. Configure dbinfo/dbcommect.php and database.sql. NOTE: In database.sql change only the database name.
4. In your mysql console run the below given command to install the database.

	`source <your-install-dir>/dbinfo/database.sql`
5. Verify that the databse is installed correctly.

## Running the app.
1. The app already has a super user, the credentials of which are 
	
    Username: `Coders`
    Password: `1000`
2. Start the server and hit localhost/your-install-dir.

