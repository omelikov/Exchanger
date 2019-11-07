<?php

namespace Exchanger\CurrencyExchangeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CurrencyRatesUpdater
 *
 * @ORM\Table(name="`currencies`")
 * @ORM\Entity(repositoryClass="Exchanger\CurrencyExchangeBundle\Repository\CurrencyRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Currency
{
    /**
     * @var int
     *
     * @ORM\Column(name="`id`", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="`code`", type="string", length=128, nullable=false)
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 128,
     * )
     */
    private $code = '';

    /**
     * @var string
     *
     * @ORM\Column(name="`name`", type="string", length=255, nullable=false)
     *
     * @Assert\Length(
     *     min = 3,
     *     max = 255,
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="`prefix`", type="string", length=128, nullable=false, options={
     *     "default": "",
     * })
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 128,
     * )
     */
    private $prefix = '';

    /**
     * @var string
     *
     * @ORM\Column(name="`suffix`", type="string", length=128, nullable=false, options={
     *     "default": "",
     * })
     *
     * @Assert\Length(
     *     min = 1,
     *     max = 128,
     * )
     */
    private $suffix = '';

    /**
     * @var float
     *
     * @ORM\Column(name="`rate`", type="decimal", precision=16, scale=8, nullable=true, options={
     *     "default": 1,
     * })
     */
    private $rate = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="`precision`", type="integer", nullable=true)
     */
    private $precision;

    /**
     * @var Collection|CurrencyRate[]
     *
     * @ORM\OneToMany(targetEntity="CurrencyRate", mappedBy="currency")
     */
    private $rates;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(name="`created_at`", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $createdAt;

    /**
     * @var \DateTime|null
     *
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(name="`updated_at`", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $updatedAt;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="`deleted_at`", type="datetime", nullable=true)
     *
     * @Assert\DateTime()
     */
    private $deletedAt;

    /**
     * Currency constructor
     */
    public function __construct()
    {
        $this->rates = new ArrayCollection();
    }

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
     * @throws \Exception
     *
     * @return Currency
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
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return Currency
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Currency
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     *
     * @return Currency
     */
    public function setPrefix(string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    /**
     * @return string
     */
    public function getSuffix(): string
    {
        return $this->suffix;
    }

    /**
     * @param string $suffix
     *
     * @return Currency
     */
    public function setSuffix(string $suffix): self
    {
        $this->suffix = $suffix;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getRate(): ?float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return Currency
     */
    public function setRate(float $rate): self
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    /**
     * @param int $precision
     *
     * @return Currency
     */
    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;

        return $this;
    }

    /**
     * @return Collection|CurrencyRate[]
     */
    public function getRates(): Collection
    {
        return $this->rates;
    }

    /**
     * @param Collection|CurrencyRate[] $rates
     *
     * @return Currency
     */
    public function setRates(Collection $rates): self
    {
        $this->rates = new ArrayCollection();

        foreach ($rates as $rate) {
            $this->addRate($rate);
        }

        return $this;
    }

    /**
     * @param CurrencyRate $rate
     *
     * @return Currency
     */
    public function addRate(CurrencyRate $rate): self
    {
        $rate->setCurrency($this);

        $this->getRates()->add($rate);

        return $this;
    }

    /**
     * @param CurrencyRate $rate
     *
     * @return Currency
     */
    public function removeRate(CurrencyRate $rate): self
    {
        $this->rates->removeElement($rate);

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
     * @return Currency
     */
    public function setCreatedAt(?\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     *
     * @return Currency
     */
    public function setUpdatedAt(?\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    /**
     * @param \DateTime|null $deletedAt
     *
     * @return Currency
     */
    public function setDeletedAt(?\DateTime $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
