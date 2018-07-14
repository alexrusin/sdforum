# SD Forum

This is an open source forum that can be used for senior design projects and other tutoring purposes

## Installation

### Prerequisites

* To run this project, you must have PHP 7 installed.
* You should setup a host on your web server for your local domain. For this you could also configure Laravel Homestead or Valet. 
* If you want use Redis as your cache driver you need to install the Redis Server. You can either use homebrew on a Mac or compile from source (https://redis.io/topics/quickstart). 

### Step 1

Begin by cloning this repository to your machine, and installing all Composer & NPM dependencies.

```bash
git clone git@github.com:alexrusin/sdforum.git
cd sdforum && composer install && npm install
npm run dev
```
TODO More descriptive command line instructions

### Step 2

Next, boot up a server and visit your forum. If using a tool like Laravel Valet, of course the URL will default to `http://sdforum.test`. 

1. Visit: `http://sdforum.test/register` to register a new forum account.

TODO Describe how to register administrator's email and create threads.