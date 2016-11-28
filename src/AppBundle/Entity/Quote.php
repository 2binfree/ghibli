<?php

namespace AppBundle\Entity;

/**
 * Quote
 */
class Quote
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $film;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $quote;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set film
     *
     * @param string $film
     *
     * @return Quote
     */
    public function setFilm($film)
    {
        $this->film = $film;

        return $this;
    }

    /**
     * Get film
     *
     * @return string
     */
    public function getFilm()
    {
        return $this->film;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Quote
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set quote
     *
     * @param string $quote
     *
     * @return Quote
     */
    public function setQuote($quote)
    {
        $this->quote = $quote;

        return $this;
    }

    /**
     * Get quote
     *
     * @return string
     */
    public function getQuote()
    {
        return $this->quote;
    }
}

