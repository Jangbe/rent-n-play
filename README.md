<p align="center"><img src="https://pakikot.my.id/admin/img/logo.png" width="200" alt="Ren N Play Logo"></p>

## About Rent n Play

RNP (Rent n Play) is an online platform that provides services for rental services for camping equipment and goods. This website provides various features that allows users to rent various camping equipment. In addition to that, this website has objectives that can facilitate customers such as cashless transactions, pick-up and drop-off services, cashless cashless transactions, equipment pick-up and drop-off services, as well as full transparency regarding equipment availability and booking status. equipment availability and booking status. This website was developed using Laravel framework to ensure optimal performance, ease of development, and user data security. In addition to Laravel, we also PHP, JavaScript, and CSS programming languages to ensure the application runs smoothly and responsively. application runs smoothly and responsively.

## Main Feature
* **Admin**
    - View Informative Report Dashboard
    - CRUD Master Data (Category, Product and User)
    - View Recent Transaction
* **Customer**
    - Manage Address Location (With interactive map from GMaps)
    - Search product
    - Checkout (Cashless using Midtrans Payment Gateway API and Delivery product)
    - Recent Transaction

## How To Install
### Requirements
If you use **[Laragon](https://laragon.org/download)** you're already have all the requirements, if not
* **[Git](https://git-scm.com/downloads)** for cloning this repository
* **[Composer](https://getcomposer.org/download)** for download all needed depedency
* **[Node-JS](https://nodejs.org/en/download/package-manager)** for install the frontend package (VueJS)
* **PHP v8.0** minimum (you can use XAMPP or something like that)

### Installing
* Open the terminal (cmd/powershell) to clone this repository or just download from the github web
```sh
git clone https://github.com/Jangbe/rent-n-play.git
```
* after cloning, just copy the `.env.example` and paste with name `.env`
* just set the configuration like the `GOOGLE_MAPS_KEY` that you can obtain in [Google Console](https://console.cloud.google.com), `MIDTRANS_CLIENT_KEY` in [Midtrans](https://midtrans.com/) and set the market location, don't forget to set the BROADCAST to pusher and get the key from the [Website](https://pusher.com/).
* after configurate the `.env`, type in cmd with the cloning/downloading directory with 
```sh
composer install
``` 
and 
```sh
npm install
```
* last but not least, type 
```sh
npm run build
```
to build the frontend VueJS

## Contributing
Thank you for considering contributing to the RnP Project, please review and you're free to fork, pull request, or open issue if you found a bug or error

## Security Vulnerabilities
If you discover a security vulnerability within this project, please send an e-mail to Devi Mulyana via devi.mulyana.0015@gmail.com. All security vulnerabilities will be promptly addressed.

## License
The Rent N Play project is open-sourced licensed under the [MIT license](https://opensource.org/licenses/MIT)