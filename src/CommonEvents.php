<?php


namespace TheAentMachine;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use TheAentMachine\Exception\CannotHandleEventException;
use TheAentMachine\Service\Service;

class CommonEvents
{
    private const NEW_SERVICE = 'NEW_SERVICE';
    private const NEW_VIRTUAL_HOST = 'NEW_VIRTUAL_HOST';
    private const NEW_IMAGE = 'NEW_IMAGE';

    /**
     * @throws CannotHandleEventException
     */
    public function dispatchService(Service $service, QuestionHelper $helper, InputInterface $input, OutputInterface $output): void
    {
        $this->canDispatchServiceOrFail($helper, $input, $output);

        Hermes::dispatchJson(self::NEW_SERVICE, $service);
    }

    /**
     * @throws CannotHandleEventException
     */
    public function canDispatchServiceOrFail(QuestionHelper $helper, InputInterface $input, OutputInterface $output): void
    {
        $canHandle = Hermes::canHandleEvent(self::NEW_SERVICE);

        if (!$canHandle) {
            $output->writeln('<error>Heads up!</error>');
            $output->writeln('It seems that Aenthill does not know how or where to store this new service. You need to install a dedicated Aent for this.');
            $output->writeln('Most of the time, you want to put this service in a docker-compose.yml file. We have a pretty good Aent for this: <info>theaentmachine/aent-docker-compose</info>.');
            $question = new Question('Do you want me to add this Aent for you? (y/n) ', 'y');
            $question->setValidator(function (string $value) {
                $value = \strtolower(trim($value));

                if ($value !== 'y' && $value !== 'n') {
                    throw new \InvalidArgumentException('Please type "y" or "n"');
                }

                return $value;
            });
            $answer = $helper->ask($input, $output, $question);

            if ($answer === 'y') {
                Hermes::setDependencies(['theaentmachine/aent-docker-compose']);
            } else {
                throw CannotHandleEventException::cannotHandleEvent(self::NEW_SERVICE);
            }
        }
    }

    /**
     * @throws CannotHandleEventException
     * @return array[] Returns the responses
     */
    public function dispatchNewVirtualHost(QuestionHelper $helper, InputInterface $input, OutputInterface $output, string $serviceName, int $virtualPort = 80, string $virtualHost = null): ?array
    {
        $this->canDispatchVirtualHostOrFail($helper, $input, $output);

        $message = [
            'service' => $serviceName,
            'virtualPort' => $virtualPort
        ];
        if ($virtualHost !== null) {
            $message['virtualHost'] = $virtualHost;
        }

        return Hermes::dispatchJson(self::NEW_VIRTUAL_HOST, $message);
    }

    /**
     * @throws CannotHandleEventException
     */
    public function canDispatchVirtualHostOrFail(QuestionHelper $helper, InputInterface $input, OutputInterface $output): void
    {
        $canHandle = Hermes::canHandleEvent(self::NEW_VIRTUAL_HOST);

        if (!$canHandle) {
            $output->writeln('<error>Heads up!</error>');
            $output->writeln('It seems that Aenthill does not know how to bind your container to a domain name. You need to install a reverse proxy for this.');
            $output->writeln('Traefik is a good reverse proxy. We have an Aent to add Traefik to your project: <info>theaentmachine/aent-traefik</info>.');
            $question = new Question('Do you want me to add this Aent for you? (y/n) ', 'y');
            $question->setValidator(function (string $value) {
                $value = \strtolower(trim($value));

                if ($value !== 'y' && $value !== 'n') {
                    throw new \InvalidArgumentException('Please type "y" or "n"');
                }

                return $value;
            });
            $answer = $helper->ask($input, $output, $question);

            if ($answer === 'y') {
                Hermes::setDependencies(['theaentmachine/aent-traefik']);
            } else {
                throw CannotHandleEventException::cannotHandleEvent(self::NEW_VIRTUAL_HOST);
            }
        }
    }

    /**
     * @throws CannotHandleEventException
     */
    public function dispatchImage(Service $service, QuestionHelper $helper, InputInterface $input, OutputInterface $output): void
    {
        $this->canDispatchImageOrFail($helper, $input, $output);

        Hermes::dispatchJson(self::NEW_IMAGE, $service);
    }

    /**
     * @throws CannotHandleEventException
     */
    public function canDispatchImageOrFail(QuestionHelper $helper, InputInterface $input, OutputInterface $output): void
    {
        $canHandle = Hermes::canHandleEvent(self::NEW_IMAGE);

        if (!$canHandle) {
            $output->writeln('<error>Heads up!</error>');
            $output->writeln('It seems that Aenthill does not know how to handle the creation of a new image. You need to install a dedicated Aent for this.');
            $output->writeln('Most of the time, you want to put the instructions in a Dockerfile. We have a pretty good Aent for this: <info>theaentmachine/aent-dockerfile</info>.');
            $question = new Question('Do you want me to add this Aent for you? (y/n) ', 'y');
            $question->setValidator(function (string $value) {
                $value = \strtolower(trim($value));

                if ($value !== 'y' && $value !== 'n') {
                    throw new \InvalidArgumentException('Please type "y" or "n"');
                }

                return $value;
            });
            $answer = $helper->ask($input, $output, $question);

            if ($answer === 'y') {
                Hermes::setDependencies(['theaentmachine/aent-dockerfile']);
            } else {
                throw CannotHandleEventException::cannotHandleEvent(self::NEW_IMAGE);
            }
        }
    }
}
