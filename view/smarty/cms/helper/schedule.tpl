{function renderSchedule schedule=null timeFormat='%Hu%M'}
    {$sorted = $schedule->getSortedOpeningHoursByWeekday()}

    <ul>
        {foreach $sorted as $openingHours}
            <li>
                {$weekday = reset($openingHours)}
                <h3>{$weekday->getStart()|date_format:'%A'}</h3>

                <ul>
                    {foreach $openingHours as $openingHour}
                        <li>
                            {translate key="label.schedule.from"} {$openingHour->getStart()|date_format:$timeFormat}
                            {translate key="label.schedule.to"} {$openingHour->getEnd()|date_format:$timeFormat}
                        </li>
                    {/foreach}
                </ul>
            </li>
        {/foreach}
    </ul>
{/function}


{function renderAvailable schedule=null dateFormat='%d/%m/%Y' timeFormat='%H.%Mu'}
    {if $schedule}

        {* Check if holiday *}
        {$h = $schedule->getCurrentHoliday()}
        {if $h}
            <span class="holiday">
                {$date = $h->getEnd()|date_format:$dateFormat}
                {$time = $h->getEnd()|date_format:$timeFormat}
                {translate key="label.holiday" date=$date time=$time}
            </span>
        {else}

            {* Check if open *}
            {$o = $schedule->getCurrentOpeningHour()}
            {if $o}
                <span class="available">
                    {$time = $o->getEnd()|date_format:$timeFormat}
                    {translate key="label.available.until" time=$time}
                </span>
            {else}

                {* Check if open next *}
                {$today = time()|date_format:'%A'}
                {$n = $schedule->getNextOpeningHour()}
                {if $n}
                    <span class="availableNext">
                        {if $n->getWeekday() == $today}
                            {$time = $n->getStart()|date_format:"`$timeFormat`"}
                            {translate key="label.available.next.today" time=$time}
                        {else if $n->getWeekday == $today + 1}
                            {$time = $n->getStart()|date_format:"`$timeFormat`"}
                            {translate key="label.available.next.tomorrow" time=$time}
                        {else}
                            {$day = $n->getStart()|date_format:"%A"}
                            {$time = $n->getStart()|date_format:"`$timeFormat`"}
                            {translate key="label.available.next" day=$day time=$time}
                        {/if}
                    </span>
                {else}

                    {* Unavailable *}
                    <span class="unavailable">
                        {translate key="label.unavailable"}
                    </span>
                {/if}
            {/if}
        {/if}
    {/if}
{/function}
