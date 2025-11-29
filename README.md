# HYPER TEKNOLOJİ - LARAVEL MİNİ UYGULAMA

Bu proje Hyper Teknoloji teknik mülaka görevi kapsamında geliştirilmiş olup, basit bir ürün listeleme ve sepet yönetimi uygulamasıdır. Uygulama şirket tarafından iletilmiş olan website üzerinden API bağlantıları ile ürünleri çekip minimal kullanıcı arayüzü ile sunmayı amaçlamakta.

# Özellikler

-   API üzerinden sayfalama ve ürünleri listeleme
-   Ürünlerin görsellerinin, fiyat bilgilerinin ve açıklamalarının görüntülenmesi
-   Sepete ürünlerin eklenmesi ve kaldırılması
-   Toplam sepet tutarının dinamik olarak hesaplanması
-   Session ID bazlı sepet yönetimi
-   Cache mekanizması
-   İstenildiği gibi API iletişiminin backend ile sağlanması
-   Minimal responsive bir arayüz (İstenildiği gibi herhangi bir framework kullanılmadı)

# Kurulum Adımları

1.Repoyu Klonlayın
git clone https://github.com/silacikgoz/hyper-laravel-task.git

2.Bağımlılıkları Yükleyin
composer install

3.Ortam Değerlerini Ayarlayın
cp .env.example .env
php artisan key:generate

-   ".env" içerisine key ve token bilgilerini ekleyin
    HYPER_API_BASE=https://api.hyperteknoloji.com.tr
    HYPER_API_KEY=**\*\*\***
    HYPER_API_TOKEN=**\*\*\***

    4.Veritabanı (XAMPP) Kurulumu
    Bu proje MySQL veritabanı kullanılarak ve geliştirme ortamında XAMPP kullanılarak çalışmakta.

-   XAMPP MySQL Sunucusunu Başlatın:
    ->XAMPP kontorl panelinden Apache ve MySQL'i başlatın
    ->VS Code üzerinde uygulamada terminal kullanarak mysql -u root -p
    -> .env dosyasında veritabanı ayarlarını yapın
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=hyper_task
    DB_USERNAME=root
    DB_PASSWORD=

    5.Migrations Çalıştırın
    php artisan migrate

    6.Sunucuyu Başlatın
    php artisan serve

7. Tarayıcınızdan aşağıdaki adrese gidin

-   "http://127.0.0.1:8000/"

# Mimari Yapı

Tüm API istekleri HyperApiService'te toplanmıştır.

-   İstenildiği gibi API Key ve Bearer Token kullanılmıştır.
-   GET/POST/PUT/DELETE Metofları kullanılmıştır
-   İstenildiği gibi hata yakalama ve logging yapılmakta

# Cache Mekanizması

Ürün listeleme API'si sayfalama (pagination) kullandığı için her sayfa farklı bir cache anahtarı ile saklanmakta

-   Cache anahtarının yapısı:
    -> hyper:products:{page}:{perPage}

-   Cache Süresi (TTL) : 5 dakika

Cache mekanizmasının bu şekilde kullanılmasının nedeni aşağıda listelenmiştir:

-   API gecikmelerini azaltmak, performansı artırmak,
-   API çağrı yükünü azaltmak

# Sepet Sisteminin Tasarımı

Sepet verileri veri tabanında tutulmaktadır (carts ve cart_items tablolarında).

-   Neden veritabanı tercih edildi?
    -> Session tabanlı olması nedeniyle login olmasa da çalışır.
    -> Cookie/LocalStorage kullanıcı tarafından silinebildiği için güvenli değil.

# Arayüz

-   Blade template kullanıldı
-   Mobil için uyumlu, responsive tasarıma sahip.
-   Herhangi bir framework kullanılmadı(Tailwind/Bootsrap yok)
-   Ürünlerin görüntülenmesi ve sepet üzerinden değişikliklerin gerçekleştirilebilmesi.

# Teknik Notlar

-   API hata yönetimi : Yanıt başarız olursa loglanır, response içindeki hata mesajı Exception olarak fırlatılır.

-   Logging: Hyper API response'ları laravel loglarına kayıt edilir.

# Bilinen Eksiklikler

-   PDF dosyasında belirtilmemesi nedeniyle bir login sistemi oluşturulmamıştır,login sistemi olmadığı için kullanıcı oturumu session ile tutulmaktadır,
-   PDF dosyasında belirtilmemesi nedeniyle ürün detay sayfası oluşturulmamıştır,
-   Cache invalidation API tarafındaki değişikliklere duyarlı değildir (TTL çözmektedir).
