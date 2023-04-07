# UFOC Database Mockup 📚

This repository contains the complete frontend and backend for a MySQL-managed Apache database. Code is written primarily in PHP, but also in CSS JavaScript, and SQL.

The goal of this project was to create a mockup database for Christopher Newport University's (CNU) University Faculty on Committees (UFOC). This committee is responsible for recordkeeping pertaining to committee and departmental membership.

## The Vision

The system should be able to track the current status of committee seats at any given moment. It should provide faculty members with information including what committees they serve on and what work they have done for them. It also will aid in making informed decisions about committee composition through a built-in election process. Ultimately, it should help resolve clerical issues within the UFOC and the committees it oversees, facilitating the process of filling committee seats tremendously. It will be successful insofar as it is able to reduce the significant overhead observed in the present system.

## The Result

See for yourself! The project timeline, all preparatory work taken, and the entire codebase is published in this 154-page google document constructed by Samuel Tyler:

https://docs.google.com/document/d/1DjevFpbf6gJFbpAEUbiKhVj7KkbwjKkyaLZr0A2OqNo/edit?usp=sharing

### Usage

This entire codebase can be interacted with by placing it in the htdocs folder of any Apache web-server hosting software you use. If you're looking for a hosting tool, I recommend <a href="https://www.apachefriends.org/">XAMPP</a>, though this was predominantly developed using <a href="https://www.mamp.info/">MAMP</a>.

The default MySQL port is hard-coded to `3308` in <a href=https://github.com/cpsc351-group1/majorsystem/blob/main/databaseconnect.php#L5>databaseconnect.php</a>, but can easily be changed.

Next, you'll need to generate the underlying database using `database.sql` as a query. Once that query completes, you should be able to access the web-server interface at `localhost:3308` (*or whatever MySQL port number you have in place of `3308`*).

![image](https://user-images.githubusercontent.com/9289863/230565079-f545bef5-8b71-40d3-8210-9ebeb6a63fdc.png)

Create an account, or sign in using <a href="https://github.com/cpsc351-group1/majorsystem/blob/main/database.sql#L192-L223">someone else's password</a>.

### Capabilities

This web-server is able 
