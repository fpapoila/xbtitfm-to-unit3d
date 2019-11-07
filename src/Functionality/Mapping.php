<?php

namespace HDInnovations\XbtitFmToUnit3d\Functionality;

use stdClass;
use Carbon\Carbon;

class Mapping
{
    /**
     * @param  string  $type
     * @param  stdClass  $data
     * @return array
     */
    public static function map(string $type, stdClass $data): array
    {
        return self::{'map'.$type}($data);
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapUser(stdClass $data): array
    {
        return [
            'id' => $data->id,
            'username' => $data->username,
            'password' => $data->password ?? null,
            'passkey' => $data->pid ?? md5(uniqid().time().microtime()),
            'rsskey' => $data->secret ?? md5(uniqid().time().microtime().$data->password),
            'group_id' => 3, // Default Member Group
            'email' => $data->email ?? null,
            'uploaded' => $data->uploaded ?? 0,
            'downloaded' => $data->downloaded ?? 0,
            'seedbonus' => str_replace('-', '', $data->seedbonus) ?? 0,
            'image' => $data->avatar ?? null,
            'title' => $data->custom_title ?? null,
            'about' => $data->about_me ?? null,
            'signature' => $data->sig ?? null,
            'hitandruns' => $data->hnr_count ?? 0,
            'active' => 1,
            'invites' => $data->invitations ?? 0,
            'last_login' => $data->lastconnect ?? Carbon::now(),
            'created_at' => $data->joined ?? Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapTorrent(stdClass $data): array
    {
        return [
            'info_hash' => $data->info_hash,
            'file_name' => str_replace('torrents/', '', $data->url),
            'name' => $data->filename,
            'slug' => self::slugify($data->filename),
            'user_id' => $data->uploader,
            'imdb' => $data->imdb ?? 0,
            'tvdb' => $data->tvdb_id ?? 0,
            'sticky' => $data->sticky ?? 0,
            'free' => $data->free === 'yes' ? 1 : 0,
            'anon' => $data->anonymous === 'true' ? 1 : 0,
            'size' => $data->size ?? 0,
            'category_id' => $data->catid ?? null,
            'announce' => $data->announce_url ?? null,
            'num_file' => 0, //xbtitFM doesnt have this!
            'description' => $data->comment ?? 'Missing',
            'seeders' => $data->seeds ?? 0,
            'leechers' => $data->leechers ?? 0,
            'times_completed' => $data->completed ?? 0,
            'created_at' => Carbon::createFromTimeString($data->data),
            'updated_at' => Carbon::createFromTimeString($data->lastupdate),
        ];
    }

    /**
     * @param  stdClass  $data
     * @return array
     */
    public static function mapCategory(stdClass $data): array
    {
        return [
            'id' => $data->id,
            'name' => $data->name,
            'slug' => self::slugify($data->name),
            'icon' => 'none',
            'image' => $data->image ?? 0,
        ];
    }

    /**
     * Return the slug of a string to be used in a URL.
     *
     * @return String
     */
    public static function slugify($text){
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicated - symbols
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
