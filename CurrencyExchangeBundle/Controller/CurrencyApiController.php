<?php

namespace Exchanger\CurrencyExchangeBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\Request;
use Exchanger\CurrencyExchangeBundle\Entity\Currency;
use Exchanger\CoreBundle\Controller\RestController;

/**
 * Class CurrencyApiController
 */
class CurrencyApiController extends RestController
{
    /**
     * Currency: Get currencies
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|array
     *
     * @Rest\Get("/currencies")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Get currencies",
     *     @SWG\Schema(
     *         type="object",
     *         @SWG\Property(
     *             property="currencies",
     *             type="array",
     *             @Model(type=Currency::class, groups={"currency-list"})
     *         ),
     *         @SWG\Property(property="activeCurrency", type="string")
     *     )
     * )
     * @SWG\Tag(name="Currency")
     *
     * @Rest\View(serializerGroups={"currency-list"})
     */
    public function getCurrenciesAction(Request $request)
    {
        $currencyRepository = $this->getDoctrine()->getRepository('YouteamCurrencyExchangeBundle:Currency');

        $host = $request->getHost();

        $siteData = $this->getParameter('sites_data');

        $data = [];
        foreach ($siteData as $siteDatum) {
            if (in_array($host, $siteDatum['hosts'])) {
                $data['currencies'] = $currencyRepository->findByCode($siteDatum['currencies']);
                $data['activeCurrency'] = $siteDatum['active_currency'];

                break;
            }
        }

        if (empty($data)) {
            $youteamCoUkData = $this->getParameter('site.youteam_co_uk');
            $data['currencies'] = $currencyRepository->findByCode($youteamCoUkData['currencies']);
            $data['activeCurrency'] = $youteamCoUkData['active_currency'];
        }

        return $data;
    }
}
