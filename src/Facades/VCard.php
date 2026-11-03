<?php

declare(strict_types=1);

namespace Dunn\VCard\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Dunn\VCard\VCard make(string $version = '3.0')
 *
 * General Properties
 * @method static \Dunn\VCard\VCard addSource(string $uri)
 * @method static \Dunn\VCard\VCard addKind(string $kind)
 * @method static \Dunn\VCard\VCard addXml(string $xml)
 *
 * Identification Properties
 * @method static \Dunn\VCard\VCard addFormattedName(string $formattedName)
 * @method static \Dunn\VCard\VCard addName(string $lastName, string $firstName, string $additional = '', string $prefix = '', string $suffix = '')
 * @method static \Dunn\VCard\VCard addNickname(string ...$nicknames)
 * @method static \Dunn\VCard\VCard addPhoto(string $urlOrBase64, string $mediaType = 'JPEG')
 * @method static \Dunn\VCard\VCard addBirthday(string $date)
 * @method static \Dunn\VCard\VCard addAnniversary(string $date)
 * @method static \Dunn\VCard\VCard addGender(string $sex, string $identity = '')
 * @method static \Dunn\VCard\VCard addGramGender(string $gramGender)
 * @method static \Dunn\VCard\VCard addPronouns(string $pronouns, string $language = '')
 * @method static \Dunn\VCard\VCard addLanguage(string $language)
 *
 * Delivery Addressing Properties
 * @method static \Dunn\VCard\VCard addAddress(string $poBox, string $extended, string $street, string $city, string $region, string $zip, string $country, string $type = 'WORK')
 *
 * Communications Properties
 * @method static \Dunn\VCard\VCard addPhoneNumber(string $number, string $type = 'CELL')
 * @method static \Dunn\VCard\VCard addEmail(string $email, string $type = 'INTERNET')
 * @method static \Dunn\VCard\VCard addImpp(string $uri, string $type = '')
 * @method static \Dunn\VCard\VCard addLang(string $languageTag, string $type = '')
 * @method static \Dunn\VCard\VCard addSocialProfile(string $uri, string $service = '')
 * @method static \Dunn\VCard\VCard addContactUri(string $uri)
 *
 * Geographical Properties
 * @method static \Dunn\VCard\VCard addTz(string $timezone)
 * @method static \Dunn\VCard\VCard addGeo(string $geoUri)
 *
 * Organizational Properties
 * @method static \Dunn\VCard\VCard addJobTitle(string $jobTitle)
 * @method static \Dunn\VCard\VCard addRole(string $role)
 * @method static \Dunn\VCard\VCard addLogo(string $urlOrBase64, string $mediaType = 'JPEG')
 * @method static \Dunn\VCard\VCard addCompany(string $organization, string ...$units)
 * @method static \Dunn\VCard\VCard addMember(string $uri)
 * @method static \Dunn\VCard\VCard addRelated(string $uri, string $type = '')
 * @method static \Dunn\VCard\VCard addOrgDirectory(string $uri)
 *
 * Explanatory Properties
 * @method static \Dunn\VCard\VCard addCategories(string ...$categories)
 * @method static \Dunn\VCard\VCard addNote(string $note)
 * @method static \Dunn\VCard\VCard addProdid(string $prodid)
 * @method static \Dunn\VCard\VCard addSound(string $urlOrBase64, string $mediaType = 'OGG')
 * @method static \Dunn\VCard\VCard addUid(string $uid)
 * @method static \Dunn\VCard\VCard addClientpidmap(int $pid, string $uri)
 * @method static \Dunn\VCard\VCard addUrl(string $url, string $type = 'WORK')
 * @method static \Dunn\VCard\VCard addCreated(string $timestamp)
 *
 * Security Properties
 * @method static \Dunn\VCard\VCard addKey(string $key, string $mediaType = 'application/pgp-keys')
 *
 * Calendar Properties
 * @method static \Dunn\VCard\VCard addFburl(string $uri, string $type = '')
 * @method static \Dunn\VCard\VCard addCaladruri(string $uri, string $type = '')
 * @method static \Dunn\VCard\VCard addCaluri(string $uri, string $type = '')
 *
 * Birth/Death Properties (RFC6474)
 * @method static \Dunn\VCard\VCard addBirthplace(string $place)
 * @method static \Dunn\VCard\VCard addDeathplace(string $place)
 * @method static \Dunn\VCard\VCard addDeathdate(string $date)
 *
 * Expertise/Hobby/Interest Properties (RFC6715)
 * @method static \Dunn\VCard\VCard addExpertise(string $area, string $level = 'average')
 * @method static \Dunn\VCard\VCard addHobby(string $hobby, string $level = 'average')
 * @method static \Dunn\VCard\VCard addInterest(string $interest, string $level = 'average')
 *
 * JSContact Properties (RFC9555)
 * @method static \Dunn\VCard\VCard addJsprop(string $key, string $jsonValue)
 *
 * Generic / Custom Properties
 * @method static \Dunn\VCard\VCard addProperty(string $name, string $value, array<string, string> $params = [])
 *
 * Build
 * @method static string build()
 *
 * @see \Dunn\VCard\VCard
 */
class VCard extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'vcard';
    }
}
