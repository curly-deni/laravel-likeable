## Installation

### Publish config and migration
```bash
php artisan vendor:publish --provider=\Zeroday\Likeable\LikeableServiceProvider --tag=migration
php artisan vendor:publish --provider=\Zeroday\Likeable\LikeableServiceProvider --tag=config
```

### import trait "Likeable" in model