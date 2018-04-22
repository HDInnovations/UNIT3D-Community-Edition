<?php
/**
 * NOTICE OF LICENSE
 *
 * UNIT3D is open-sourced software licensed under the GNU General Public License v3.0
 * The details is bundled with this project in the file LICENSE.txt.
 *
 * @project    UNIT3D
 * @license    https://www.gnu.org/licenses/agpl-3.0.en.html/ GNU Affero General Public License v3.0
 * @author     Poppabear
 */

namespace App\Repositories;

interface WishInterface
{
    public function all();
    public function omdbRequest($imdb);
    public function findById($id);
    public function findByTitle($title);
    public function exists($uid, $id);
    public function isGranted($id);
    public function getSource($id);
    public function getUserWishes($uid);
    public function create(array $data);
    public function delete($id);
}