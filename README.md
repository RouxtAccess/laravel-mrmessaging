# Mr Messaging Notification Channel for Laravel

# Version Support
Laravel 10+

# Installation

Send SMS notifications in Laravel powered by [Mr Messaging](https://www.mrmessaging.net/).

## Step 1: Install the package via composer:

```bash
composer require rouxtaccess/laravel-mrmessaging
```

The package will automatically register it's service provider and merge it's config


## Step 2: Add your Mr Messaging credentials to your `.env` file:

Required env config: (see advanced Configuration below for more)
```
    MR_MESSAGING_USERNAME=
    MR_MESSAGING_PASSWORD=
    MR_MESSAGING_DELIVERY_ENABLED=true
```

## Step 3: Add your routing information to the notifiable model
We support a few ways to route the SMS to the correct number.

The default is to use the `routeNotificationForSms` method on the notifiable model.

This method should return the phone number to send the SMS to.

But if you'd like to specifically configure the phone number for MrMessaging differently, you can also use a method `routeNotificationForMrMessaging` on the notifiable model to override the default `routeNotificationForSms`.

Example:
```php
    public function routeNotificationForSms($notification)
    {
        return $this->customer_msisdn;
    }
```

> Note: Mr Messaging takes in a E.164 formatted number without the leading plus. On our side we will strip any + or spaces from the phone number before sending it to MrMessaging


## Step 4: Enable the channel for this notification

Add `'mrmessaging'` to your notification's `via` method.

```php
    public function via(object $notifiable): array
    {
        return ['mail', 'mrmessaging'];
    }
```

## Step 5: Format your message
Add the `toMrMessaging` method to your notification.

```php
   use Illuminate\Notifications\Messages\MrMessagingMessage;
   
   public function toMrMessaging(object $notifiable): MrMessagingMessage
    {
        return (new MrMessagingMessage)
            ->content('Your SMS message content');
    }
```

## Step 6: Send your notification

That's it! Send your notifications and they'll fire over to Mr Messaging.

```php
    $user->notify(new YourNotification());
```



# Additional Information

## Configuration
You can publish the config file with:
```bash
php artisan vendor:publish --provider="Illuminate\Notifications\MrMessagingServiceProvider"
```
or
```bash
php artisan vendor:publish --tag=laravel-mrmessaging-config
```

You will notice that there are a few additional configuration options in the config file around storing eventIDs

## Event Tracking
Mr Messaging supports event tracking through a two different mechanisms.

### Option 1: Laravel Notification Events 
By default, you can always hook into [Laravel's events](https://laravel.com/docs/11.x/notifications#notification-sent-event) to listen for the `NotificationSentEvent` event

We return an array of all the Event ID's that we get from MrMessaging

It is an array, not a string because of multi-part messages (MrMessaging gives us an eventID for each part)

### Option 2: Cache
If enable via the config, we will store the eventIDs from all sms messages sent in the cache for a configurable amount of time (defaulting to 24 hours)

This is very useful if you just want a very simple solution to track the eventID until you get a delivery report back from Mr Messaging

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.