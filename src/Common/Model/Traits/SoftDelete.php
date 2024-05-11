<?php

namespace App\Common\Model\Traits;

trait SoftDelete
{
    protected \DateTime|null $deletedAt;

    /**
     * Delete the document
     *
     * @return void
     */
    public function delete(): void
    {
        $this->deletedAt = date_create_from_format(DATE_ATOM, date(DATE_ATOM));
    }

    /**
     * Restore the document
     *
     * @return void
     */
    public function restore(): void
    {
        $this->deletedAt = null;
    }

    /**
     * Return true if the document is deleted
     *
     * @return bool
     */
    public function isDeleted(): bool
    {
        return $this->deletedAt !== null;
    }

    /**
     * Return the date when the document was deleted
     *
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
}