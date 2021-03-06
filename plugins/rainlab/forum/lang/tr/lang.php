<?php

return [
    'topics' => [
        'component_name' => 'Konu Listesi',
        'component_description' => 'Tüm konu başlıklarının bir listesini görüntüler.',
        'per_page' => 'Sayfa başına gösterilecek konu',
        'per_page_validation' => 'Geçersiz değer girdiniz'
    ],
    'topic' => [
        'page_name' => 'Konu Sayfası',
        'page_help' => 'Konu için kullanılacak sayfa adı.'
    ],
    'member' => [
        'page_name' => 'Üyeler Sayfası',
        'page_help' => 'Her bir üye için kullanılacak sayfa adı.'
    ],
    'channel' => [
        'component_name' => 'Kanal',
        'component_description' => 'Bir kanala ait mesajların listesini görüntüler.',
        'page_name' => 'Kanal Sayfası',
        'page_help' => 'Kanal için kullanılacak sayfa adı.'
    ],
    'channels' => [
        'new_channel' => 'Yeni Kanal',
        'sure' => 'Emin misiniz?',
        'delete' => 'Sil',
        'manage' => 'Kanal Sırasını Değiştir',
        'return' => 'Kanallara Dön',
        'name' => 'Kanallar',
        'create' => 'Kanal Oluştur',
        'update' => 'Kanalları Düzenle',
        'preview' => 'Kanalları Önizle',
        'manage' => 'Kanalları Yönet',
        'creating' => 'Kanal Oluşturuluyor...',
        'create' => 'Oluştur',
        'createnclose' => 'Oluştur ve Kapat',
        'cancel' => 'İptal',
        'or' => 'veya',
        'returnlist' => 'Kanal listesine dön',
        'saving' => 'Kanal Kaydediliyor...',
        'save' => 'Kaydet',
        'savenclose' => 'Kaydet ve Kapat',
        'deleting' => 'Kanal Siliniyor...',
        'really' => 'Silmek istediğinize emin misiniz?',
        'list_name' => 'Kanal Listesi',
        'list_desc' => 'Tüm görülebilir kanalların listesini gösterir.'
    ],
    'slug' => [
        'name' => 'Takma ad',
        'desc' => 'URL rota parametresi kanalı takma adına göre bulmaya yarar. Takma adı direk yazabilirsiniz.'
    ],
    'frontend' => [
        'notopic' => 'Bu kanalda konu yok.'
    ],

    'plugin' => [
        'name' => 'Forum',
        'description' => 'Basit, sayfa içerisine gömülebilir forum'
    ],
    'data' => [
        'title' => 'Başlık',
        'desc' => 'Tanım',
        'slug' => 'Takma ad',
        'parent' => 'Üst Kanal',
        'noparent' => '-- Üst Kanal Yok --',
        'moderated' => 'Sadece Yöneticiler',
        'is_mod' => 'Bu kanalı sadece yöneticiler görebilir.',
        'hidden' => 'Gizli',
        'is_hidden' => 'Bu kanalı ana listeden gizle.'
    ],
    'settings' => [
        'username' => 'Kullanıcı Adı',
        'username_comment' => 'Bu kullanıcıyı forumda temsil eden isim',
        'moderator' => 'Forum yöneticisi',
        'moderator_comment' => 'Eğer bu üyenin yönetici olmasını istiyorsanız kutuyu işaretleyin.',
        'banned' => 'Forumdan banlanmış.',
        'banned_comment' => 'Eğer bu üyenin forumda banlı olmasını istiyorsanız kutuyu işaretleyin.',
        'forum_username' => 'Forum Kullanıcı Adı',
        'channels' => 'Forum kanalları',
        'channels_desc' => 'Forum kanallarını bu bölümden yönetebilirsiniz.'
    ],
    'embedch' => [
        'channel_name' => 'Sayfaya Kanal Göm',
        'channel_self_desc' => 'Herhangi bir sayfaya bir forum ekleyebilirsiniz.',
        'channel_title' => 'Üst Kanal',
        'channel_desc' => 'Yeni kanalın içine ekleneceği üst kanalı belirleyin',
        'embed_title' => 'Embed kod parametresi',
        'embed_desc' => 'Oluşturulan kanal için eşsiz bir kod. Yönlendirme parametresi kullanılabilir.',
        'topic_name' => 'Konu kod parametresi',
        'topic_desc' => 'URL rota parametresi kanalı takma adına göre bulmaya yarar.'
    ],
    'embedtopic' => [
        'topic_name' => 'Konu Göm',
        'topic_self_desc' => 'Herhangi bir sayfaya bir konu ekler.',
        'target_name' => 'Hedef Kanal',
        'target_desc' => 'Oluşturulacak kanalı seçin',
        'embed_title' => 'Embed kod parametresi',
        'embed_desc' => 'Oluşturulan konu veya kanal için eşsiz bir kod. Yönlendirme parametresi kullanılabilir.',
    ],
    'memberpage' => [
        'name' => 'Üye',
        'self_desc' => 'Üyenin bilgilerini ve istatistiklerini gösterir.',
        'slug_name' => 'Takma ad parametresi',
        'slug_desc' => 'Bir forum üyesi için eşsiz bir kod. Yönlendirme parametresi kullanılabilir.',
        'view_title' => 'Gösterim modu',
        'view_desc' => 'Üye gösterim modunu belirleyin.',
        'ch_title' => 'Kanal sayfası',
        'ch_desc' => 'Kanal için bir sayfa adı belirleyin.',
        'topic_title' => 'Konu sayfası',
        'topic_desc' => 'Konu için bir sayfa adı belirleyin.'
    ],
    'topicpage' => [
        'name' => 'Konu',
        'desc' => 'Bir konuyu ve mesajlarını gösterir.',
        'slug_name' => 'Takma ad parametresi',
        'slug_desc' => 'Oluşturulan konu için eşsiz bir kod. Yönlendirme parametresi kullanılabilir.',
        'channel_title' => 'Kanal Sayfası',
        'channel_desc' => 'Kanal için bir sayfa adı belirleyin.',
    ]
];