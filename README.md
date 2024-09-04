# ReadMe UniRent
## Application Purpose
This web application has been made to help students looking for a house and owners looking for student tenants.
## Application Functionalities
### Students
The platform allows students to look for accommodations that meet some of their needs: city, university, period and year of stay. Additional filters are available for use, such as reviews of the accommodation and owner or monthly price range.

In order to accommodate also the owners' needs, we ask the students to provide some additional information that will not be displayed to anyone, but serves only for the personalization of the research of the appartment. These informations are the ownership of an animal that they mean to take with them during the stay and if they have the habit of smoking. Since some owners will also have preferences on the sex of the tenant, this information is also required.
During the search, only the good fits are shown to the student.

When students open an advertisement, they can choose to book a visit for that accommodation. In phase of publishing, the owner gives an availability for visit hours and days. Students can, therefore, select the weekday and hours among the available ones. Note that one hour is not displayed if occupied by somebody else and the visit is booked for the first available weekday from the current date.

Inside the advertisement, students can also reserve the accommodation. The reservation process is devided in various phases:
1. The student opens the reservation panel and submits the application for the reservation of the accommodation for a certain period of a year. The reservation will be displayed in the section dedicated to the pending ones.
2. The owner can either accept or deny the application within two days from the student request. If the owner does not do anything, the application is automatically accepted when the time is up.
3. The reservation is moved from the pending section to the accepted one, where the student is required to pay to sign the contract. The amount to pay is listed in the deposit section that the owner inserted (note that it can be 0, but it it still required to insert a credit card to associate with the contract).

Once the contract is started, students can leave one review (per stay) to roommates, owner and accommodation. These reviews need to follow the guidelines, listed in the dedicated section of the application, otherwhise the reviews can be reported. The report could lead to the review deletition and, if necessary, to the user ban. There are also other means to be banned, therefore we encurange to read the guidelines before using the application.

### Owner
The application allows landlords to publish accommodation advertisements in order to find tenants that satisfy their needs and expectations.

The advertiserment requires the owner to insert some informations, such as the address, photos, descriptions, number of available places and tenants preferences. The price that the owner is required to insert needs to be devided between monthly price and deposit.

The accommodation ad can be deactivated in case the owner doesn't want any more new reservations and contracts. The deletition of the ad, however, can be performed only if there are no ongoing or future contracts.

The owners have the responsability to examinate the student reservations withing the 2 days time frame that is given to them, otherwhise they will automatically accept the reservation, which cannot be deleted if it becomes a contract paied by the student.

During the stay of a student, the owner can leave a review to the tenant (one per stay).

## Installation Guide
# Requisites
- XAMPP 8.2.12 or higher:
  - PHP 8.2.4 or higher;
  - Apache 2.4.58 or higher;
  - MariaDB 10.4.32 or higher;
  - phpMyAdmin 5.2.1 or higher;
- composer 2.7.4 or higher;
# How To Install
The first thing to do is unzip the folder file inside the XAMPP directory `htdocs`.

In order to make the application work with your current database setup, you will need to open the file `/xampp/htdocs/UniRent/Configuration/Config.php` and change the credentials listed to the right ones for your need. Note that if you have no password for your database, we advice to set one up.

To ensure that you don't have any problems with some sample elements already present inside the application, you will need to change some settings on your XAMPP configuration.

**If you are working from a Windows machine**

- Go to the file: `C:/xampp/mysql/bin/my.ini`;

**If you are working from a MacOS machine**

- Go to the file: `~/XAMPP/xamppfiles/etc/my.cnf`;

**In both cases continue as follows:**

- Search for the element `max_allowed_packet` (It should appear a total of 2 times);
- Ensure that the values associated with that varable is at least 1024M in both of the declarations.

To make sure the application works well, we need you to run the following command into the UniRent folder:

`composer install`

`composer dump-autoload`

Now you are ready to go, the only thing left to do is to turn on the servers. In MacOS and Windows environments you will just need to open the XAMPP control panel (manager-osx on Mac) and start Apache and mySQL servers. For Linux users, you will need to start the servers via terminal, by writing the following commands:

`sudo /opt/lampp/lampp start Apache`

`sudo /opt/lampp/lampp start mySQL`

Now you can enjoy the application experience by writing the following link inside your preferred browser: "localhost/UniRent/".
