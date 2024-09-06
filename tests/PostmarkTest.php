<?php

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Vormkracht10\Mails\Enums\Events\Mapping;
use Vormkracht10\Mails\Models\Mail as MailModel;
use Vormkracht10\Mails\Models\MailEvent;

use function Pest\Laravel\assertDatabaseHas;
use function Pest\Laravel\post;

it('can receive incoming delivery webhook from postmark', function () {
    Mail::send([], [], function (Message $message) {
        $message->to('mark@vormkracht10.nl')
            ->from('local@computer.nl')
            ->cc('cc@vk10.nl')
            ->bcc('bcc@vk10.nl')
            ->subject('Test')
            ->text('Text')
            ->html('<p>HTML</p>');
    });

    $mail = MailModel::latest()->first();

    post(URL::signedRoute('mails.webhook', ['driver' => 'postmark']), [
        'Metadata' => [
            config('mails.headers.uuid') => $mail?->uuid,
        ],
        'RecordType' => 'Delivery',
        'ServerID' => 23,
        'MessageStream' => 'outbound',
        'MessageID' => '00000000-0000-0000-0000-000000000000',
        'Recipient' => 'mark@vormkracht10.nl',
        'Tag' => 'welcome-email',
        'DeliveredAt' => '2023-05-19T22:09:32Z',
        'Details' => 'Test delivery webhook details',
    ])->assertAccepted();

    assertDatabaseHas((new MailEvent)->getTable(), [
        'type' => Mapping::DELIVERY->value,
    ]);
});

it('can receive incoming bounce webhook from postmark', function () {
    Mail::send([], [], function (Message $message) {
        $message->to('mark@vormkracht10.nl')
            ->from('local@computer.nl')
            ->cc('cc@vk10.nl')
            ->bcc('bcc@vk10.nl')
            ->subject('Test')
            ->text('Text')
            ->html('<p>HTML</p>');
    });

    $mail = MailModel::latest()->first();

    post(URL::signedRoute('mails.webhook', ['driver' => 'postmark']), [
        'Metadata' => [
            config('mails.headers.uuid') => $mail?->uuid,
        ],
        'RecordType' => 'Bounce',
        'ID' => 42,
        'Type' => 'HardBounce',
        'TypeCode' => 1,
        'Name' => 'Hard bounce',
        'Tag' => 'Test',
        'MessageID' => '00000000-0000-0000-0000-000000000000',
        'ServerID' => 1234,
        'MessageStream' => 'outbound',
        'Description' => 'The server was unable to deliver your message (ex => unknown user, mailbox not found).',
        'Details' => 'Test bounce details',
        'Email' => 'john@example.com',
        'From' => 'sender@example.com',
        'BouncedAt' => '2023-05-21T02:51:39Z',
        'DumpAvailable' => true,
        'Inactive' => true,
        'CanActivate' => true,
        'Subject' => 'Test subject',
        'Content' => 'Test content',
    ])->assertAccepted();

    assertDatabaseHas((new MailEvent)->getTable(), [
        'type' => Mapping::BOUNCE->value,
    ]);
});

it('can receive incoming complaint webhook from postmark', function () {
    Mail::send([], [], function (Message $message) {
        $message->to('mark@vormkracht10.nl')
            ->from('local@computer.nl')
            ->cc('cc@vk10.nl')
            ->bcc('bcc@vk10.nl')
            ->subject('Test')
            ->text('Text')
            ->html('<p>HTML</p>');
    });

    $mail = MailModel::latest()->first();

    post(URL::signedRoute('mails.webhook', ['driver' => 'postmark']), [
        'Metadata' => [
            config('mails.headers.uuid') => $mail?->uuid,
        ],
        'RecordType' => 'SpamComplaint',
        'ID' => 42,
        'Type' => 'SpamComplaint',
        'TypeCode' => 100001,
        'Name' => 'Spam complaint',
        'Tag' => 'Test',
        'MessageID' => '00000000-0000-0000-0000-000000000000',
        'ServerID' => 1234,
        'MessageStream' => 'outbound',
        'Description' => 'The subscriber explicitly marked this message as spam.',
        'Details' => 'Test spam complaint details',
        'Email' => 'john@example.com',
        'From' => 'sender@example.com',
        'BouncedAt' => '2023-05-21T02:51:39Z',
        'DumpAvailable' => true,
        'Inactive' => true,
        'CanActivate' => false,
        'Subject' => 'Test subject',
        'Content' => 'Test content',
    ])->assertAccepted();

    assertDatabaseHas((new MailEvent)->getTable(), [
        'type' => Mapping::COMPLAINT->value,
    ]);
});

it('can receive incoming open webhook from postmark', function () {
    Mail::send([], [], function (Message $message) {
        $message->to('mark@vormkracht10.nl')
            ->from('local@computer.nl')
            ->cc('cc@vk10.nl')
            ->bcc('bcc@vk10.nl')
            ->subject('Test')
            ->text('Text')
            ->html('<p>HTML</p>');
    });

    $mail = MailModel::latest()->first();

    post(URL::signedRoute('mails.webhook', ['driver' => 'postmark']), [
        'Metadata' => [
            config('mails.headers.uuid') => $mail?->uuid,
        ],
        'RecordType' => 'Open',
        'FirstOpen' => true,
        'Client' => [
            'Name' => 'Chrome 35.0.1916.153',
            'Company' => 'Google',
            'Family' => 'Chrome',
        ],
        'OS' => [
            'Name' => 'OS X 10.7 Lion',
            'Company' => 'Apple Computer, Inc.',
            'Family' => 'OS X 10',
        ],
        'Platform' => 'WebMail',
        'UserAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
        'ReadSeconds' => 5,
        'Geo' => [
            'CountryISOCode' => 'RS',
            'Country' => 'Serbia',
            'RegionISOCode' => 'VO',
            'Region' => 'Autonomna Pokrajina Vojvodina',
            'City' => 'Novi Sad',
            'Zip' => '21000',
            'Coords' => '45.2517,19.8369',
            'IP' => '188.2.95.4',
        ],
        'MessageID' => '00000000-0000-0000-0000-000000000000',
        'MessageStream' => 'outbound',
        'ReceivedAt' => '2023-05-21T02:51:39Z',
        'Tag' => 'welcome-email',
        'Recipient' => 'john@example.com',
    ])->assertAccepted();

    assertDatabaseHas((new MailEvent)->getTable(), [
        'type' => Mapping::OPEN->value,
    ]);
});

it('can receive incoming click webhook from postmark', function () {
    Mail::send([], [], function (Message $message) {
        $message->to('mark@vormkracht10.nl')
            ->from('local@computer.nl')
            ->cc('cc@vk10.nl')
            ->bcc('bcc@vk10.nl')
            ->subject('Test')
            ->text('Text')
            ->html('<p>HTML</p>');
    });

    $mail = MailModel::latest()->first();

    post(URL::signedRoute('mails.webhook', ['driver' => 'postmark']), [
        'Metadata' => [
            config('mails.headers.uuid') => $mail?->uuid,
        ],
        'RecordType' => 'Click',
        'ClickLocation' => 'HTML',
        'Client' => [
            'Name' => 'Chrome 35.0.1916.153',
            'Company' => 'Google',
            'Family' => 'Chrome',
        ],
        'OS' => [
            'Name' => 'OS X 10.7 Lion',
            'Company' => 'Apple Computer, Inc.',
            'Family' => 'OS X 10',
        ],
        'Platform' => 'Desktop',
        'UserAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36',
        'OriginalLink' => 'https://example.com',
        'Geo' => [
            'CountryISOCode' => 'RS',
            'Country' => 'Serbia',
            'RegionISOCode' => 'VO',
            'Region' => 'Autonomna Pokrajina Vojvodina',
            'City' => 'Novi Sad',
            'Zip' => '21000',
            'Coords' => '45.2517,19.8369',
            'IP' => '188.2.95.4',
        ],
        'MessageID' => '00000000-0000-0000-0000-000000000000',
        'MessageStream' => 'outbound',
        'ReceivedAt' => '2023-05-21T02:51:39Z',
        'Tag' => 'welcome-email',
        'Recipient' => 'john@example.com',
    ])->assertAccepted();

    assertDatabaseHas((new MailEvent)->getTable(), [
        'type' => Mapping::CLICK->value,
        'link' => 'https://example.com',
    ]);
});
