<table class="document-table">
    <?php
        use Models\Competition;
        use Models\User;
        $formatter = new IntlDateFormatter(
            'fr_FR',
            IntlDateFormatter::LONG,
            IntlDateFormatter::NONE,
            'Europe/Paris'
        );

        $competition_id = (int)"{{application_id_competition}}";
        $user_id = (int)"{{application_id_user}}";
        $user = User::fetch($user_id);
        $competition = Competition::fetch($competition_id);

        // Formats the competition date to dd/MM/yyyy
        $date_competition = $formatter->format($competition->getStartDateTime());
    ?>
    <thead>
        <th class="competition">Competition</th>
        <th class="nom">Nom</th>
        <th class="prenom">Prenom</th>
        <th class="validation">Validation</th>
        <th class="actions">Actions</th>
    </thead>
    <tbody>
        <tr>
            <td class="competition">
                <?php 
                    echo ("
                        <a href=\"/planning/view/?competition_id=$competition_id\">$date_competition</a>
                    ");
                ?>
            </td>
            <td class="nom">
                <?php
                    echo htmlspecialchars($user->getName());
                ?>
            </td>
            <td class="prenom">
                <?php
                    echo htmlspecialchars($user->getSurname());
                ?>
            </td>
            <td class="validation">
                En attente
            </td>
            <td class="actions">
                <div class="btn-wrapper inline">
                    <?php 
                        echo ("
                            <a class=\"btn filled\" id=\"validate-application-btn\" href=\"/dashboard/competitions/validate/?user=$user_id&competition=$competition_id&action=validateParticipation&redirect=/dashboard/competitions\">Valider</a>
                        ");
                        echo ("
                            <a class=\"btn outline\" id=\"invalidate-application-btn\" href=\"/dashboard/competitions/validate/?user=$user_id&competition=$competition_id&action=invalidateParticipation&redirect=/dashboard/competitions\">Invalider</a>
                        ");
                    ?>
                </div>
            </td>
        </tr>
    </tbody>
</table>