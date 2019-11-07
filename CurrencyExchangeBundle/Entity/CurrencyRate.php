<?php

namespace Exchanger\CurrencyExchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CurrencyRatesUpdater
 *
 * @ORM\Table(name="currency_rates")
 * @ORM\Entity
 */
class CurrencyRate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="rate", type="decimal", precision=16, scale=8, nullable=true)
     */
    protected $rate = 1;

    /**
     * @var Currency
     *
     * @ORM\ManyToOne(targetEntity="Currency", inversedBy="rates")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", nullable=false)
     */
    protected $currency;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $createdAt = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     *
     * @return CurrencyRate
     *
     * @throws \Exception
     */
    public function setId(?int $id): self
    {
        if (!is_null($id) && $id <= 0) {
            throw new \Exception('Bad value for id field. The value should be greater than 0.');
        }

        $this->id = $id;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return CurrencyRate
     */
    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return null|Currency
     */
    public function getCurrency(): ?Currency
    {
        return $this->currency;
    }

    /**
     * @param Currency $currency
     *
     * @return CurrencyRate
     */
    public function setCurrency(Currency $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $createdAt
     *
     * @return CurrencyRate
     */
    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
