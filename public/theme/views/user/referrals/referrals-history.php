<?php
defined('FIR') OR exit();
/**
 * The template for displaying Example Create page
 */
?>

<?php 
    function getRelativeTime($date) {
        $now = new DateTime();
        $Date = new DateTime($date);
        $interval = $Date->diff($now);

        if ($interval->y >= 1) {
            $years = $interval->y;
            $suffix = ($years === 1) ? 'year' : 'years';
            return $years . ' ' . $suffix . ' ago';
        } elseif ($interval->m >= 1) {
            $months = $interval->m;
            $suffix = ($months === 1) ? 'month' : 'months';
            return $months . ' ' . $suffix . ' ago';
        } elseif ($interval->d >= 1) {
            $days = $interval->d;
            $suffix = ($days === 1) ? 'day' : 'days';
            return $days . ' ' . $suffix . ' ago';
        } elseif ($interval->h >= 1) {
            $hours = $interval->h;
            $suffix = ($hours === 1) ? 'hour' : 'hours';
            return $hours . ' ' . $suffix . ' ago';
        } elseif ($interval->i >= 1) {
            $minutes = $interval->i;
            $suffix = ($minutes === 1) ? 'minute' : 'minutes';
            return $minutes . ' ' . $suffix . ' ago';
        } else {
            $seconds = $interval->s;
            $suffix = ($seconds === 1) ? 'second' : 'seconds';
            return $seconds . ' ' . $suffix . ' ago';
        }
    }
?>
<?php 
    function formatCurrency($currency, $amount): string
    {
        switch ($currency) {
            case '€':
                return '€ ' . number_format($amount, 2);
            case '£':
                return '£ ' . number_format($amount, 2);
            case '$':
                return '$ ' . number_format($amount, 2);
            default:
                return $currency . ' ' . number_format($amount, 2);
        }
    }
?>
<div class="position-relative">
    <div class="containt-parent">
        <div class="main-containt">
            <!-- main-containt -->
            <div class="text-center">
                <p class="mb-0 gilroy-Semibold f-26 text-dark theme-tran r-f-20 text-uppercase">Referrals</p>
                <p class="mb-0 gilroy-medium text-gray-100 f-16 r-f-12 mt-2 tran-title p-inline-block">Track your progress, monitor your referrals, and climb up the ranks to unlock exciting rewards.</p>
            </div>

            <div class="invested-Profit-plan bg-white mt-24">
                <div class="plan_profit">
                    <div class="row col-gap-20">
                        <div class="<?php echo !empty($data['ranking-invest']['min_invest']) ? 'col-xl-3 ' : 'col-xl-12'; ?>">
                            <div class="inv-plan">
                                <p class="mb-0 f-16 leading-20 text-gray gilroy-medium">Your Rank</p>
                                <div class="mb-0 d-flex gilroy-Semibold mt-2 gap-12">
                                    <span class="f-26 leading-32 text-dark platinum">
                                        <?= e(!empty($data['current-ranking']['name']) ? $data['current-ranking']['name'] : 'Newbie') ?>
                                    </span>
                                    <span class="inv-status-badge f-11 leading-14 bg-success text-white d-flex justify-content-center align-items-center align-self-center">Active</span>
                                </div>
                                <p class="mb-0 f-16 leading-18 gilroy-medium fst-italic text-gray gilroy-medium mt-2">
                                    <?= e(!empty($data['next-ranking']['name']) ? 'Next rank is ' . $data['next-ranking']['name'] : 'You have reached the highest rank') ?>.
                                </p>
                            </div>
                        </div>

                        <?php if (!empty($data['ranking-invest']['min_invest'])): ?>
                            <div class="col-xl-3">
                                <div class="invest_profit bg-white-50">
                                    <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium">To unlock next rank</p>
                                    <p class="mb-0 f-22 leading-24 text-primary gilroy-Semibold mt-2">Invest: <?= formatCurrency(!empty($data['user']['currency']) ? $data['user']['currency'] : '', !empty($data['ranking-invest']['min_invest']) ? $data['ranking-invest']['min_invest'] : '0') ?></p>
                                    <p class="mb-0 f-16 leading-20 text-dark l-sp mt-5p gilroy-medium">Refer: <?= e(!empty($data['ranking-referral']['min_referral']) ? $data['ranking-referral']['min_referral'] : '0') ?></p>
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <div class="invest_profit bg-white-50">
                                    <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium">You have invested</p>
                                    <p class="mb-0 f-22 leading-24 text-primary gilroy-Semibold mt-2"><?=formatCurrency($data['user']['currency'], $data['count-invests'])?></p>
                                    <p class="mb-0 f-16 leading-20 text-dark l-sp mt-5p gilroy-medium">
                                        Remaining: 
                                        <?php
                                            $ranking_remaining = $data['ranking-remaining']['remaining'];
                                            // Check if $ranking_remaining is negative, if so, default it to '0.00'
                                            if ($ranking_remaining < 0) {
                                                $ranking_remaining = 0;
                                            }
                                        ?>
                                        <?=formatCurrency($data['user']['currency'], $ranking_remaining)?>
                                    </p>
                                </div>
                            </div>

                            <div class="col-xl-3">
                                <div class="invest_profit bg-white-50">
                                    <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium">You have referred</p>
                                    <p class="mb-0 f-22 leading-24 text-primary gilroy-Semibold mt-2"><?=e($data['count-referrals'])?></p>
                                    <p class="mb-0 f-16 leading-20 text-dark l-sp mt-5p gilroy-medium">
                                        Remaining: 
                                        <?php
                                            $referral_remaining = $data['referrals-remaining']['remaining'];
                                            // Check if $referral_remaining is negative, if so, default it to '0.00'
                                            if ($referral_remaining < 0) {
                                                $referral_remaining = 0;
                                            }
                                        ?>
                                        <?=e($referral_remaining)?>
                                    </p>
                                </div>
                            </div>
                        <?php endif ?>
                    </div>
                </div>
            </div>

            <div class="invested-Profit-plan bg-white mt-24">
                <div class="plan_profit">
                    <div class="row col-gap-20">
                        <div class="col-xl-6">
                            <div class="invest_capital bg-white h-100 d-flex flex-column">
                                <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium text-start">Rank Bonus</p>
                                <p class="mb-0 f-22 leading-24 text-dark gilroy-Semibold mt-2 text-start"><?= formatCurrency($data['user']['currency'], $data['ranking-bonus']['bonus']) ?></p>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <div class="invest_capital bg-white h-100 d-flex flex-column">
                                <p class="mb-0 f-14 leading-17 text-gray-100 gilroy-medium text-start">Commissions Earned</p>
                                <p class="mb-0 f-22 leading-24 text-dark gilroy-Semibold mt-2 text-start"><?= formatCurrency($data['user']['currency'], $data['commissions']) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <nav>
                        <div class="nav-tab-parent d-flex justify-content-center mt-4">
                            <div class="d-flex p-2 border-1p rounded-pill gap-1 bg-white nav-tab-child">
                                <a href="<?=$this->siteUrl()?>/user/referrals" class="tablink-edit text-gray-100 tabactive">Referrals</a>
                                <a href="<?=$this->siteUrl()?>/user/ranking" class="tablink-edit text-gray-100">Ranking</a>
                            </div>
                        </div>
                    </nav>

                    <div class="settings-wrapper inv-details wrapper">
                        <div class="sliding-content-parent bg-white mt-3">
                            <?php if (empty($data['get-referrals'])): ?>
                                <div class="content-2">
                                    <div class="notfound mt-16 bg-white p-4">
                                        <div class="d-flex flex-wrap justify-content-center align-items-center gap-26">
                                            <div class="image-notfound">
                                                <img src="<?=$this->siteUrl()?>/<?=$this->themePath()?>/assets/dist/images/not-found.png" class="img-fluid">
                                            </div>
                                            <div class="text-notfound">
                                                <p class="mb-0 f-20 leading-25 gilroy-medium text-dark">Sorry! No data found.</p>
                                                <p class="mb-0 f-16 leading-24 gilroy-regular text-gray-100 mt-12">As of now, there are no referral records available.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="content-1">
                                    <table class="table table-p table-bordered">
                                        <tbody>
                                            <?php foreach ($data['get-referrals'] as $referral): ?>
                                                <tr>
                                                    <td>
                                                        <div class="details">
                                                            <p class="mb-0 f-13 leading-16 text-gray-100 gilroy-medium">Details</p>
                                                            <p class="mb-0 f-15 leading-18 text-primary gilroy-medium mt-2">
                                                                <?=e($referral["firstname"])?> <?=e($referral["lastname"])?>
                                                            </p>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <div class="details">
                                                            <p class="mb-0 f-13 leading-16 text-gray-100 gilroy-medium text-end">Joined</p>
                                                            <p class="mb-0 f-15 leading-18 text-primary gilroy-medium mt-2 text-end l-sp64">
                                                                <?=e(getRelativeTime($referral['registration_date']))?>
                                                            </p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main-containt -->
        </div>
    </div>
</div>
