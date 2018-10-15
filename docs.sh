echo "SET access-token:docs-72ddf2e5cbc2b150de19a6dda84d476e docs-10101010101" | redis-cli
php artisan cache:clear
php artisan view:clear
rm -rf ./public/docs
php artisan api:generate --routePrefix="api/*" --header="Authorization: Access docs-72ddf2e5cbc2b150de19a6dda84d476e" --noPostmanCollection
sudo chmod -R 777 storage/